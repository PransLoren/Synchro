<?php

// App\Http\Controllers\ProjectInvitationController.php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ProjectModel;
use Illuminate\Http\Request;
use App\Notifications\EmailProjectInvitationNotification;
use App\Notifications\EmailAcceptInvitationNotification;
use App\Notifications\EmailRejectInvitationNotification;

class ProjectInvitationController extends Controller
{
    public function invite(Request $request, ProjectModel $project)
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

        // Check if the user is already invited or a member
        if ($project->users->contains($user)) {
            return back()->with('error', 'User is already invited or is a member of this project.');
        }

        // Send invitation via email
        $user->notify(new EmailProjectInvitationNotification($project));

        return back()->with('success', 'Invitation sent successfully.');
    }

    public function acceptInvitation(ProjectModel $project)
    {
        // Find the authenticated user
        $user = auth()->user();

        // Attach the user to the project
        $project->users()->attach($user);

        // Send notification
        $user->notify(new EmailAcceptInvitationNotification($project));

        return back()->with('success', 'Invitation accepted successfully!');
    }

    public function rejectInvitation(ProjectModel $project)
    {
        // Find the authenticated user
        $user = auth()->user();

        // Detach the user from the project
        $project->users()->detach($user);

        // Send notification
        $user->notify(new EmailRejectInvitationNotification($project));

        return back()->with('success', 'Invitation rejected successfully!');
    }
}

