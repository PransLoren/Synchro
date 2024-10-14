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

        // Check if the user is already a member
        if ($project->users->contains($user)) {
            return back()->with('error', 'User is already a member of this project.');
        }

        // Check if there's already a pending invitation
        $invitation = ProjectInvitation::where('project_id', $project->id)
            ->where('email', $request->email)
            ->where('status', ProjectInvitation::STATUS_PENDING)
            ->first();

        if ($invitation) {
            return back()->with('error', 'User has already been invited and the invitation is pending.');
        }

        // Create a new invitation
        ProjectInvitation::create([
            'project_id' => $project->id,
            'email' => $request->email,
            'status' => ProjectInvitation::STATUS_PENDING,
        ]);

        // Send invitation via email
        $user->notify(new EmailProjectInvitationNotification($project)); // Make sure this notification class matches the Project model type

        return back()->with('success', 'Invitation sent successfully.');
    }

    public function acceptInvitation(Project $project)
    {
        $user = auth()->user();

        // Find the invitation
        $invitation = ProjectInvitation::where('project_id', $project->id)
            ->where('email', $user->email)
            ->where('status', ProjectInvitation::STATUS_PENDING)
            ->first();

        if (!$invitation) {
            return back()->with('error', 'Invalid or expired invitation.');
        }

        // Attach the user to the project
        $project->users()->syncWithoutDetaching([$user->id]);

        // Update the invitation status
        $invitation->update(['status' => ProjectInvitation::STATUS_ACCEPTED]);

        return redirect()->route('userdashboard')->with('success', 'Invitation accepted successfully!');
    }

    public function rejectInvitation(ProjectInvitation $invitation)
    {
        // Update the invitation status to rejected
        $invitation->update(['status' => ProjectInvitation::STATUS_REJECTED]);

        // Optional: Notify the user
        $user = User::where('email', $invitation->email)->first();
        if ($user) {
            $user->notify(new EmailRejectInvitationNotification($invitation->project));
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
