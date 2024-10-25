<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\ProjectInvitation;
use App\Models\Notification; 
use Illuminate\Http\Request;
use App\Notifications\EmailProjectInvitationNotification;
use App\Notifications\EmailAcceptInvitationNotification;
use App\Notifications\EmailRejectInvitationNotification;

class ProjectInvitationController extends Controller
{
    public function invite(Request $request, Project $project)
    {
        if (auth()->id() !== $project->created_by) {
            return redirect()->back()->with('error', 'Only the project creator can invite members.');
        }

        // Validate the email input
        $request->validate([
            'email' => 'required|email',
        ]);

        try {
            $user = User::where('email', $request->email)->firstOrFail();

            if ($project->users->contains($user)) {
                return redirect()->back()->with('error', 'User is already a member of this project.');
            }

            $existingInvitation = ProjectInvitation::where('project_id', $project->id)
                ->where('email', $request->email)
                ->where('status', ProjectInvitation::STATUS_PENDING)
                ->first();

            if ($existingInvitation) {
                return redirect()->back()->with('error', 'An invitation is already pending for this user.');
            }

            ProjectInvitation::create([
                'project_id' => $project->id,
                'email' => $request->email,
                'status' => ProjectInvitation::STATUS_PENDING,
            ]);

            $user->notify(new EmailProjectInvitationNotification($project));

            Notification::create([
                'user_id' => $project->created_by,
                'notifiable_type' => Project::class,
                'notifiable_id' => $project->id,
                'type' => 'invitation_sent',
                'message' => "An invitation has been sent to '{$user->name}' for the project '{$project->class_name}'.",
                'is_read' => false,
                'created_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Invitation sent successfully.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send invitation: ' . $e->getMessage());
        }
    }


    public function acceptInvitation(Project $project)
    {
        $user = auth()->user(); 

        $invitation = ProjectInvitation::where('project_id', $project->id)
            ->where('email', $user->email)
            ->where('status', ProjectInvitation::STATUS_PENDING)
            ->first();

        if (!$invitation) {
            return back()->with('error', 'Invalid or expired invitation.');
        }

        $project->users()->syncWithoutDetaching([$user->id]);
        $invitation->update(['status' => ProjectInvitation::STATUS_ACCEPTED]);

        $creator = User::find($project->created_by); 

        if ($creator) {
            \App\Models\Notification::create([
                'user_id' => $creator->id,
                'notifiable_type' => Project::class,
                'notifiable_id' => $project->id,
                'type' => 'invitation_accepted',
                'message' => "The user '{$user->name}' has accepted the invitation to join project '{$project->class_name}'.",
                'is_read' => false,
                'created_at' => now(),
            ]);
        }

        return redirect()->route('userdashboard')->with('success', 'Invitation accepted successfully!');
    }


    public function rejectInvitation(ProjectInvitation $invitation)
    {
        $invitation->update(['status' => ProjectInvitation::STATUS_REJECTED]);

        $user = User::where('email', $invitation->email)->first();
        $project = Project::find($invitation->project_id);
        $creator = User::find($project->created_by);

        if ($creator && $user) {
            \App\Models\Notification::create([
                'user_id' => $creator->id,
                'notifiable_type' => Project::class,
                'notifiable_id' => $project->id,
                'type' => 'invitation_rejected',
                'message' => "The user '{$user->name}' has rejected the invitation to join project '{$project->class_name}'.",
                'is_read' => false,
                'created_at' => now(),
            ]);
        }

        if ($user) {
            $user->notify(new EmailRejectInvitationNotification($project));
        }

        return redirect()->route('student.dashboard')->with('success', 'Invitation rejected successfully.');
    }

    public function showInvitations()
    {
        $user = auth()->user();
        $invitations = ProjectInvitation::where('email', $user->email)
                                        ->where('status', ProjectInvitation::STATUS_PENDING)
                                        ->get();
        
        return view('invitations.index', compact('invitations'));
    }
}
