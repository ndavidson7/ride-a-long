var base = $(location).attr('origin');
var url = base + '/notifications';
$.getJSON(url, function (data) {
  var notifs = [];

  $.each(data['requests'], function (key, val) {
    var ride = key;
    $.each(val, function (key, val) {
      notifs.push("<li><button class='dropdown-item' data-bs-toggle='modal' data-bs-target='#requestModal' data-bs-ride='" + ride + "' data-bs-user='" + val + "'>Someone requested to join your ride!</button></li>");
    });
  });
  $.each(data['responses'], function (key, val) {
    if (val == 1) {
      notifs.push("<li><button class='dropdown-item' data-bs-toggle='modal' data-bs-target='#responseModal' data-bs-ride='" + key + "' data-bs-response=Accepted>Your request was accepted!</button></li>");
    } else {
      notifs.push("<li><button class='dropdown-item' data-bs-toggle='modal' data-bs-target='#responseModal' data-bs-ride='" + key + "' data-bs-response=Denied>Your request was denied!</button></li>");
    }
  });

  if (notifs.length == 0) {
    $('#notifsBadge').remove();
  } else {
    $('#numNotifs').text(notifs.length);
  }
  $('#notifs').html(notifs.join(""));

  $('#requestModal').on('show.bs.modal', function (event) {
    // Determine which request was clicked
    var ride = $(event.relatedTarget).attr("data-bs-ride");
    var user = $(event.relatedTarget).attr("data-bs-user");

    var url = base + '/index.php?command=requestinfo&ride=' + ride + '&user=' + user;
    $.get(url, function (data) {
      $('#requestRide').html(data.orig_addr + " &#8594; " + data.dest_addr);
      $('#requestUser').text(data.rider.first_name + " " + data.rider.last_name + " (" + data.rider.email + ")");
    });

    $('#deny').attr('href', base + '/index.php?command=respond&ride=' + ride + '&user=' + user + '&response=0');
    $('#accept').attr('href', base + '/index.php?command=respond&ride=' + ride + '&user=' + user + '&response=1');
  });

  $('#responseModal').on('show.bs.modal', function (event) {
    var ride = $(event.relatedTarget).attr("data-bs-ride");
    var response = $(event.relatedTarget).attr("data-bs-response");

    var url = base + '/rides/' + ride;
    $.get(url, function (data) {
      $('#responseRide').html(data.origin.address + " &#8594; " + data.destination.address);
      $('#response').text(response);
    });

    $('#read').attr('href', base + '/index.php?command=deleteresponse&ride=' + ride);
  });
});
