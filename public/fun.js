
    function addProject(projectId) {
        $.ajax({
            type: 'POST',
            url: $('#addForm' + projectId).attr('action'),
            data: $('#addForm' + projectId).serialize(),
            success: function(response) {
                $('#addMessage' + projectId).html('<div class="alert alert-success">' + response.success + '</div>');
            },
            error: function(xhr, status, error) {
                $('#addMessage' + projectId).html('<div class="alert alert-danger">' + xhr.responseJSON.message + '</div>');
            }
        });
    }

