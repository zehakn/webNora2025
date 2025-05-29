/**
 * Note Service for handling all note-related operations in the frontend
 */
var NoteService = {
    /**
     * Initialize the note service
     */
    init: function() {
        // Any initialization code for notes
    },
    
    /**
     * Get all notes for the current user
     * @param {Function} successCallback - Function to call on success
     * @param {Function} errorCallback - Function to call on error
     */
    getUserNotes: function(successCallback, errorCallback) {
        const user = UserService.getCurrentUser();
        if (!user) {
            if (errorCallback) errorCallback("User not logged in");
            return;
        }
        
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "notes/user",
            type: "GET",
            contentType: "application/json",
            dataType: "json",
            beforeSend: function(xhr) {
                const token = localStorage.getItem("user_token");
                if (token) {
                    xhr.setRequestHeader("Authorization", "Bearer " + token);
                }
            },
            success: function(result) {
                if (successCallback) successCallback(result);
            },
            error: function(xhr) {
                if (errorCallback) {
                    errorCallback(xhr.responseText || "Failed to fetch notes");
                } else {
                    toastr.error(xhr.responseText || "Failed to fetch notes");
                }
            }
        });
    },
    
    /**
     * Get notes for a specific user (Admin only)
     * @param {Number} userId - User ID to get notes for
     * @param {Function} successCallback - Function to call on success
     * @param {Function} errorCallback - Function to call on error
     */
    getNotesByUserId: function(userId, successCallback, errorCallback) {
        if (!UserService.isAdmin()) {
            if (errorCallback) errorCallback("Permission denied");
            return;
        }
        
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "notes/user/" + userId,
            type: "GET",
            contentType: "application/json",
            dataType: "json",
            beforeSend: function(xhr) {
                const token = localStorage.getItem("user_token");
                if (token) {
                    xhr.setRequestHeader("Authorization", "Bearer " + token);
                }
            },
            success: function(result) {
                if (successCallback) successCallback(result);
            },
            error: function(xhr) {
                if (errorCallback) {
                    errorCallback(xhr.responseText || "Failed to fetch notes");
                } else {
                    toastr.error(xhr.responseText || "Failed to fetch notes");
                }
            }
        });
    },
    
    /**
     * Get a note by ID
     * @param {Number} id - Note ID
     * @param {Function} successCallback - Function to call on success
     * @param {Function} errorCallback - Function to call on error
     */
    getNoteById: function(id, successCallback, errorCallback) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "notes/" + id,
            type: "GET",
            contentType: "application/json",
            dataType: "json",
            beforeSend: function(xhr) {
                const token = localStorage.getItem("user_token");
                if (token) {
                    xhr.setRequestHeader("Authorization", "Bearer " + token);
                }
            },
            success: function(result) {
                if (successCallback) successCallback(result);
            },
            error: function(xhr) {
                if (errorCallback) {
                    errorCallback(xhr.responseText || "Failed to fetch note");
                } else {
                    toastr.error(xhr.responseText || "Failed to fetch note");
                }
            }
        });
    },
    
    /**
     * Create a new note
     * @param {Object} noteData - Note data
     * @param {Function} successCallback - Function to call on success
     * @param {Function} errorCallback - Function to call on error
     */
    createNote: function(noteData, successCallback, errorCallback) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "notes",
            type: "POST",
            data: JSON.stringify(noteData),
            contentType: "application/json",
            dataType: "json",
            beforeSend: function(xhr) {
                const token = localStorage.getItem("user_token");
                if (token) {
                    xhr.setRequestHeader("Authorization", "Bearer " + token);
                }
            },
            success: function(result) {
                toastr.success("Note created successfully");
                if (successCallback) successCallback(result);
            },
            error: function(xhr) {
                if (errorCallback) {
                    errorCallback(xhr.responseText || "Failed to create note");
                } else {
                    toastr.error(xhr.responseText || "Failed to create note");
                }
            }
        });
    },
    
    /**
     * Update an existing note
     * @param {Number} id - Note ID
     * @param {Object} noteData - Updated note data
     * @param {Function} successCallback - Function to call on success
     * @param {Function} errorCallback - Function to call on error
     */
    updateNote: function(id, noteData, successCallback, errorCallback) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "notes/" + id,
            type: "PUT",
            data: JSON.stringify(noteData),
            contentType: "application/json",
            dataType: "json",
            beforeSend: function(xhr) {
                const token = localStorage.getItem("user_token");
                if (token) {
                    xhr.setRequestHeader("Authorization", "Bearer " + token);
                }
            },
            success: function(result) {
                toastr.success("Note updated successfully");
                if (successCallback) successCallback(result);
            },
            error: function(xhr) {
                if (errorCallback) {
                    errorCallback(xhr.responseText || "Failed to update note");
                } else {
                    toastr.error(xhr.responseText || "Failed to update note");
                }
            }
        });
    },
    
    /**
     * Delete a note
     * @param {Number} id - Note ID
     * @param {Function} successCallback - Function to call on success
     * @param {Function} errorCallback - Function to call on error
     */
    deleteNote: function(id, successCallback, errorCallback) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "notes/" + id,
            type: "DELETE",
            contentType: "application/json",
            dataType: "json",
            beforeSend: function(xhr) {
                const token = localStorage.getItem("user_token");
                if (token) {
                    xhr.setRequestHeader("Authorization", "Bearer " + token);
                }
            },
            success: function(result) {
                toastr.success("Note deleted successfully");
                if (successCallback) successCallback(result);
            },
            error: function(xhr) {
                if (errorCallback) {
                    errorCallback(xhr.responseText || "Failed to delete note");
                } else {
                    toastr.error(xhr.responseText || "Failed to delete note");
                }
            }
        });
    }
};

// Initialize note service when document is ready
$(document).ready(function() {
    NoteService.init();
});
