var url = $(location).attr('origin') == 'http://localhost' ? 'http://localhost/notifications' : 'https://cs4640.cs.virginia.edu/nid3dhu/project/notifications';
$.getJSON(url, function(data) {
  var notifs = [];

  $.each(data['requests'], function(key, val) {
    var ride = key;
    $.each(val, function(key, val) {
      notifs.push("<li><button class='dropdown-item' data-bs-toggle='modal' data-bs-target='#requestModal' data-bs-ride='" + ride + "' data-bs-user='" + val + "'>Someone requested to join your ride!</button></li>");
    });
  });
  $.each(data['responses'], function(key, val) {
    if (val == "accept") {
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

  $('#requestModal').on('show.bs.modal', function(event) {
    // Determine which request was clicked
    var ride = $(event.relatedTarget).attr("data-bs-ride");
    var user = $(event.relatedTarget).attr("data-bs-user");

    var base = $(location).attr('origin') == 'http://localhost' ? 'http://localhost/' : 'https://cs4640.cs.virginia.edu/nid3dhu/project/';
    var url = base+'index.php?command=requestinfo&ride='+ride+'&user='+user;
    $.get(url, function(data) {
      $('#requestRide').html(data.orig_addr + " &#8594; " + data.dest_addr);
      $('#requestUser').text(data.user.first_name+" "+data.user.last_name+" ("+data.user.email+")");
    });

    $('#deny').attr('href', base+'index.php?command=respond&ride='+ride+'&user='+user+'&response=deny');
    $('#accept').attr('href', base+'index.php?command=respond&ride='+ride+'&user='+user+'&response=accept');
  });

  $('#responseModal').on('show.bs.modal', function(event) {
    var ride = $(event.relatedTarget).attr("data-bs-ride");
    var response = $(event.relatedTarget).attr("data-bs-response");

    var base = $(location).attr('origin') == 'http://localhost' ? 'http://localhost/' : 'https://cs4640.cs.virginia.edu/nid3dhu/project/';
    var url = base+'rides/'+ride;
    $.get(url, function(data) {
      $('#responseRide').html(data.orig_addr + " &#8594; " + data.dest_addr);
      $('#response').text(response);
    });

    $('#read').attr('href', base+'index.php?command=deleteresponse&ride='+ride);
  });
});
