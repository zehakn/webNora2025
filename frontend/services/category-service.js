/**
 * Category Service for handling all category-related operations in the frontend
 */
var CategoryService = {
    /**
     * Initialize the category service
     */
    init: function() {
        // Initialize category-related functionality
    },
    
    /**
     * Get all categories
     * @param {Function} successCallback - Function to call on success
     * @param {Function} errorCallback - Function to call on error
     */
    getAllCategories: function(successCallback, errorCallback) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "test-categories",
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
                    errorCallback(xhr.responseText || "Failed to fetch categories");
                } else {
                    toastr.error(xhr.responseText || "Failed to fetch categories");
                }
            }
        });
    },
    
    /**
     * Get category by name
     * @param {String} name - Category name
     * @param {Function} successCallback - Function to call on success
     * @param {Function} errorCallback - Function to call on error
     */
    getCategoryByName: function(name, successCallback, errorCallback) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "test-category-by-name/" + encodeURIComponent(name),
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
                    errorCallback(xhr.responseText || "Failed to fetch category");
                } else {
                    toastr.error(xhr.responseText || "Failed to fetch category");
                }
            }
        });
    },
    
    /**
     * Create a new category
     * @param {Object} categoryData - Category data
     * @param {Function} successCallback - Function to call on success
     * @param {Function} errorCallback - Function to call on error
     */
    createCategory: function(categoryData, successCallback, errorCallback) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "test-category",
            type: "POST",
            data: JSON.stringify(categoryData),
            contentType: "application/json",
            dataType: "json",
            beforeSend: function(xhr) {
                const token = localStorage.getItem("user_token");
                if (token) {
                    xhr.setRequestHeader("Authorization", "Bearer " + token);
                }
            },
            success: function(result) {
                toastr.success("Category created successfully");
                if (successCallback) successCallback(result);
            },
            error: function(xhr) {
                if (errorCallback) {
                    errorCallback(xhr.responseText || "Failed to create category");
                } else {
                    toastr.error(xhr.responseText || "Failed to create category");
                }
            }
        });
    },
    
    /**
     * Update an existing category
     * @param {Number} id - Category ID
     * @param {Object} categoryData - Updated category data
     * @param {Function} successCallback - Function to call on success
     * @param {Function} errorCallback - Function to call on error
     */
    updateCategory: function(id, categoryData, successCallback, errorCallback) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "test-category/" + id,
            type: "PUT",
            data: JSON.stringify(categoryData),
            contentType: "application/json",
            dataType: "json",
            beforeSend: function(xhr) {
                const token = localStorage.getItem("user_token");
                if (token) {
                    xhr.setRequestHeader("Authorization", "Bearer " + token);
                }
            },
            success: function(result) {
                toastr.success("Category updated successfully");
                if (successCallback) successCallback(result);
            },
            error: function(xhr) {
                if (errorCallback) {
                    errorCallback(xhr.responseText || "Failed to update category");
                } else {
                    toastr.error(xhr.responseText || "Failed to update category");
                }
            }
        });
    },
    
    /**
     * Delete a category
     * @param {Number} id - Category ID
     * @param {Function} successCallback - Function to call on success
     * @param {Function} errorCallback - Function to call on error
     */
    deleteCategory: function(id, successCallback, errorCallback) {
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "test-category/" + id,
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
                toastr.success("Category deleted successfully");
                if (successCallback) successCallback(result);
            },
            error: function(xhr) {
                if (errorCallback) {
                    errorCallback(xhr.responseText || "Failed to delete category");
                } else {
                    toastr.error(xhr.responseText || "Failed to delete category");
                }
            }
        });
    },
    
    /**
     * Get category options for dropdown menus
     * @param {Function} callback - Function to call with formatted category options
     */
    getCategoryOptions: function(callback) {
        this.getAllCategories(
            function(categories) {
                let options = categories.map(function(category) {
                    return {
                        id: category.id,
                        text: category.name
                    };
                });
                
                // Sort alphabetically by name
                options.sort(function(a, b) {
                    return a.text.localeCompare(b.text);
                });
                
                if (callback) callback(options);
            },
            function(error) {
                console.error("Error fetching category options:", error);
                if (callback) callback([]);
            }
        );
    },
    
    /**
     * Render a category badge
     * @param {String} categoryName - Name of the category
     * @returns {String} HTML string for the category badge
     */
    renderCategoryBadge: function(categoryName) {
        return `<span class="badge bg-info category-badge">${Utils.sanitizeHTML(categoryName || 'Uncategorized')}</span>`;
    },
    
    /**
     * Group items by category
     * @param {Array} items - Array of items with category property
     * @returns {Object} Object with categories as keys and arrays of items as values
     */
    groupByCategory: function(items) {
        if (!items || !Array.isArray(items)) return {};
        
        return items.reduce(function(groups, item) {
            const category = item.category ? item.category : 'Uncategorized';
            if (!groups[category]) {
                groups[category] = [];
            }
            groups[category].push(item);
            return groups;
        }, {});
    }
};

// Initialize the category service when document is ready
$(document).ready(function() {
    CategoryService.init();
});
