// assets/js/main.js

// Function to submit a complaint
function submitComplaint(formSelector, messageSelector) {
    $(formSelector).on('submit', function (e) {
      e.preventDefault();
      $.ajax({
        url: '../backend/api/submit_complaint.php',
        method: 'POST',
        data: $(this).serialize(),
        success: function () {
          $(messageSelector).html('<div class="alert alert-success">Complaint submitted successfully!</div>');
          $(formSelector)[0].reset();
        },
        error: function () {
          $(messageSelector).html('<div class="alert alert-danger">Failed to submit complaint.</div>');
        }
      });
    });
  }
  
  // Function to fetch complaints
  function fetchComplaints(tableSelector) {
    $.get('../backend/api/fetch_complaints.php', function (data) {
      let rows = '';
      data.forEach(complaint => {
        rows += `<tr>
          <td>${complaint.id}</td>
          <td>${complaint.citizen_name}</td>
          <td>${complaint.district}</td>
          <td>${complaint.status}</td>
          <td>
            <select class="form-control status-dropdown" data-id="${complaint.id}">
              <option ${complaint.status === 'Pending' ? 'selected' : ''}>Pending</option>
              <option ${complaint.status === 'Resolved' ? 'selected' : ''}>Resolved</option>
            </select>
          </td>
        </tr>`;
      });
      $(tableSelector).html(rows);
    });
  }
  
  // Function to fetch district-wise report
  function fetchReport(reportSelector) {
    $.get('../backend/api/fetch_report.php', function (data) {
      let rows = '';
      data.forEach(row => {
        rows += `<tr>
          <td>${row.district}</td>
          <td>${row.resolved}</td>
          <td>${row.pending}</td>
          <td>${row.total}</td>
        </tr>`;
      });
      $(reportSelector).html(rows);
    });
  }
  
  // Function to update complaint status
  function bindStatusUpdate(callback) {
    $(document).on('change', '.status-dropdown', function () {
      const complaintId = $(this).data('id');
      const newStatus = $(this).val();
      $.post('../backend/api/update_status.php', {
        id: complaintId,
        status: newStatus
      }, callback);
    });
  }
  