<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use App\Models\ProjectInvitation;
use Illuminate\Http\Request;
use App\Notifications\EmailProjectInvitationNotification;
use App\Notifications\EmailAcceptInvitationNotification;
use App\Notifications\EmailRejectInvitationNotification;

class ProjectInvitationController extends Controller
{
    public function invite(Request $request, Project $project)
    {
        // Validate user input
        $request->validate([
            'email' => 'required|email'
        ]);

        // Find user by email
        $user = User::where('email', $request->email)->first();

        // Check if the user exists
        if (!$user) {
            return back()->with('error', 'User with provided email not found.');
        }

     
        if ($project->users->contains($user)) {
            return back()->with('error', 'User is already a member of this project.');
        }

        $invitation = ProjectInvitation::where('project_id', $project->id)
            ->where('email', $request->email)
            ->where('status', ProjectInvitation::STATUS_PENDING)
            ->first();

        if ($invitation) {
            return back()->with('error', 'User has already been invited and the invitation is pending.');
        }
        ProjectInvitation::create([
            'project_id' => $project->id,
            'email' => $request->email,
            'status' => ProjectInvitation::STATUS_PENDING,
        ]);

        $user->notify(new EmailProjectInvitationNotification($project)); 
        return back()->with('success', 'Invitation sent successfully.');
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
