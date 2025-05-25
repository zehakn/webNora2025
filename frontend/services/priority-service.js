/**
 * Priority Service for handling all priority-related operations in the frontend
 */
var PriorityService = {
    /**
     * Initialize the priority service
     */
    init: function() {
        // Initialize priority-related functionality
    },
    
    /**
     * Get all priorities
     * @param {Function} successCallback - Function to call on success
     * @param {Function} errorCallback - Function to call on error
     */
    getAllPriorities: function(successCallback, errorCallback) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "test-priorities",
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
                    errorCallback(xhr.responseText || "Failed to fetch priorities");
                } else {
                    toastr.error(xhr.responseText || "Failed to fetch priorities");
                }
            }
        });
    },
    
    /**
     * Get priority by name
     * @param {String} name - Priority name
     * @param {Function} successCallback - Function to call on success
     * @param {Function} errorCallback - Function to call on error
     */
    getPriorityByName: function(name, successCallback, errorCallback) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "test-priority-by-name/" + encodeURIComponent(name),
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
                    errorCallback(xhr.responseText || "Failed to fetch priority");
                } else {
                    toastr.error(xhr.responseText || "Failed to fetch priority");
                }
            }
        });
    },
    
    /**
     * Create a new priority
     * @param {Object} priorityData - Priority data
     * @param {Function} successCallback - Function to call on success
     * @param {Function} errorCallback - Function to call on error
     */
    createPriority: function(priorityData, successCallback, errorCallback) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "test-priority",
            type: "POST",
            data: JSON.stringify(priorityData),
            contentType: "application/json",
            dataType: "json",
            beforeSend: function(xhr) {
                const token = localStorage.getItem("user_token");
                if (token) {
                    xhr.setRequestHeader("Authorization", "Bearer " + token);
                }
            },
            success: function(result) {
                toastr.success("Priority created successfully");
                if (successCallback) successCallback(result);
            },
            error: function(xhr) {
                if (errorCallback) {
                    errorCallback(xhr.responseText || "Failed to create priority");
                } else {
                    toastr.error(xhr.responseText || "Failed to create priority");
                }
            }
        });
    },
    
    /**
     * Update an existing priority
     * @param {Number} id - Priority ID
     * @param {Object} priorityData - Updated priority data
     * @param {Function} successCallback - Function to call on success
     * @param {Function} errorCallback - Function to call on error
     */
    updatePriority: function(id, priorityData, successCallback, errorCallback) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "test-priority/" + id,
            type: "PUT",
            data: JSON.stringify(priorityData),
            contentType: "application/json",
            dataType: "json",
            beforeSend: function(xhr) {
                const token = localStorage.getItem("user_token");
                if (token) {
                    xhr.setRequestHeader("Authorization", "Bearer " + token);
                }
            },
            success: function(result) {
                toastr.success("Priority updated successfully");
                if (successCallback) successCallback(result);
            },
            error: function(xhr) {
                if (errorCallback) {
                    errorCallback(xhr.responseText || "Failed to update priority");
                } else {
                    toastr.error(xhr.responseText || "Failed to update priority");
                }
            }
        });
    },
    
    /**
     * Delete a priority
     * @param {Number} id - Priority ID
     * @param {Function} successCallback - Function to call on success
     * @param {Function} errorCallback - Function to call on error
     */
    deletePriority: function(id, successCallback, errorCallback) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "test-priority/" + id,
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
                toastr.success("Priority deleted successfully");
                if (successCallback) successCallback(result);
            },
            error: function(xhr) {
                if (errorCallback) {
                    errorCallback(xhr.responseText || "Failed to delete priority");
                } else {
                    toastr.error(xhr.responseText || "Failed to delete priority");
                }
            }
        });
    },
    
    /**
     * Get priority options for dropdown menus
     * @param {Function} callback - Function to call with formatted priority options
     */
    getPriorityOptions: function(callback) {
        this.getAllPriorities(
            function(priorities) {
                let options = priorities.map(function(priority) {
                    return {
                        id: priority.id,
                        text: priority.name,
                        color: priority.color || null,
                        value: priority.value || 0
                    };
                });
                
                // Sort by priority value if available
                options.sort(function(a, b) {
                    return b.value - a.value;
                });
                
                if (callback) callback(options);
            },
            function(error) {
                console.error("Error fetching priority options:", error);
                if (callback) callback([]);
            }
        );
    },
    
    /**
     * Render a priority badge with appropriate styling
     * @param {String} priorityName - Name of the priority
     * @param {String} defaultColor - Default color if priority not found
     * @returns {String} HTML string for the priority badge
     */
    renderPriorityBadge: function(priorityName, defaultColor = "#6c757d") {
        // Create a placeholder badge with the priority name
        let badgeHtml = `<span class="badge bg-secondary">${priorityName || 'Unknown'}</span>`;
        
        // Try to find the priority to get its color
        this.getPriorityByName(priorityName, 
            function(priority) {
                if (priority && priority.color) {
                    const textColor = this.getContrastTextColor(priority.color);
                    badgeHtml = `<span class="badge" style="background-color: ${priority.color}; color: ${textColor}">${priority.name}</span>`;
                    // Replace the placeholder badge with the styled one
                    $(`.priority-badge[data-priority="${priorityName}"]`).replaceWith(badgeHtml);
                }
            }.bind(this)
        );
        
        return `<span class="priority-badge" data-priority="${priorityName}">${badgeHtml}</span>`;
    },
    
    /**
     * Calculate contrast text color (black or white) based on background color
     * @param {String} bgColor - Background color in hex format
     * @returns {String} Either black or white as the appropriate text color
     */
    getContrastTextColor: function(bgColor) {
        // Remove the hash if it exists
        bgColor = bgColor.replace('#', '');
        
        // Convert to RGB
        const r = parseInt(bgColor.substr(0, 2), 16);
        const g = parseInt(bgColor.substr(2, 2), 16);
        const b = parseInt(bgColor.substr(4, 2), 16);
        
        // Calculate luminance
        const luminance = (0.299 * r + 0.587 * g + 0.114 * b) / 255;
        
        // Return black or white based on luminance
        return (luminance > 0.5) ? '#000000' : '#FFFFFF';
    },
    
    /**
     * Get a color for priority level
     * @param {Number} level - Priority level (higher number = higher priority)
     * @returns {String} Hex color code
     */
    getPriorityColor: function(level) {
        // Default color scheme for priorities
        const colors = {
            1: "#28a745", // Low - Green
            2: "#17a2b8", // Medium-Low - Teal
            3: "#ffc107", // Medium - Yellow
            4: "#fd7e14", // Medium-High - Orange
            5: "#dc3545"  // High - Red
        };
        
        return colors[level] || "#6c757d"; // Default to gray if level not found
    }
};

// Initialize the priority service when document is ready
$(document).ready(function() {
    PriorityService.init();
});
