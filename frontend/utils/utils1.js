/**
 * Utils.js - Utility functions for the application
 * 
 * This file contains helper functions for:
 * - DataTable initialization and rendering
 * - JWT token parsing and user data extraction
 */

let Utils = {
  /**
   * Initialize or reinitialize a DataTable
   * @param {string} table_id - ID of the table element without # prefix
   * @param {Array} columns - Column configuration for the DataTable
   * @param {Array} data - Data to display in the table
   * @param {number} pageLength - Number of rows per page
   */
  datatable: function(table_id, columns, data, pageLength = 15) {
    // Destroy existing DataTable if it exists
    if ($.fn.dataTable.isDataTable("#" + table_id)) {
      $("#" + table_id)
        .DataTable()
        .destroy();
    }
    
    // Initialize new DataTable with provided configuration
    $("#" + table_id).DataTable({
      data: data,
      columns: columns,
      pageLength: pageLength,
      lengthMenu: [2, 5, 10, 15, 25, 50, 100, "All"],
    });
  },

  /**
   * Parse a JWT token to extract the payload
   * @param {string} token - JWT token to parse
   * @return {Object|null} - Decoded payload or null if token is invalid
   */
  parseJwt: function(token) {
    if (!token) return null;
    try {
      const payload = token.split('.')[1];
      const decoded = atob(payload);
      return JSON.parse(decoded);
    } catch (e) {
      console.error("Invalid JWT token", e);
      return null;
    }
  }
};
