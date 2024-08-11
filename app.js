document.addEventListener("DOMContentLoaded", function() {
    const sidebar = document.getElementById("sidebar");
    const sidebarToggle = document.getElementById("sidebar-toggle");
    const sidebarCancel = document.getElementById("sidebar-cancel");

    // Toggle sidebar visibility
    sidebarToggle.addEventListener("click", function() {
        sidebar.classList.toggle("d-none");
        sidebar.classList.toggle("d-block");
    });

    // Close sidebar
    sidebarCancel.addEventListener("click", function() {
        sidebar.classList.add("d-none");
        sidebar.classList.remove("d-block");
    });

    loadEvents(); // Load and render events when the page is loaded
});

function loadEvents() {
    const eventsList = document.getElementById("events-list");

    fetch("get-events.php")
        .then(response => {
            if (!response.ok) {
                throw new Error("Network response was not ok");
            }
            return response.json();
        })
        .then(data => {
            console.log("Events data:", data); // Debugging line
            eventsList.innerHTML = ''; // Clear any existing events
            if (data.length === 0) {
                eventsList.innerHTML = '<p>No events found.</p>'; // Display a message if no events are found
            } else {
                data.forEach(event => {
                    const eventCard = document.createElement("div");
                    eventCard.classList.add("event-card", "col-md-6");
                    eventCard.setAttribute('data-id', event.id); // Set data-id attribute for the event
                    eventCard.innerHTML = `
                        <h3>${event.title}</h3>
                        <p>${event.description}</p>
                        <p><strong>Date:</strong> ${event.date}</p>
                        <p><strong>Time:</strong> ${event.time}</p> <!-- Show time -->
                        <div class="event-buttons">
                            <button class="btn btn-primary btn-sm edit-btn">Edit</button>
                            <button class="btn btn-danger btn-sm delete-btn">Delete</button>
                        </div>
                    `;
                    eventsList.appendChild(eventCard);
                });
            }
        })
        .catch(error => console.error("Error fetching events:", error));
}

document.addEventListener('click', function(e) {
    if (e.target.classList.contains('edit-btn')) {
        const eventCard = e.target.closest('.event-card');
        const eventId = eventCard.getAttribute('data-id');
        const title = eventCard.querySelector('h3').innerText;
        const description = eventCard.querySelector('p').innerText;
        const date = eventCard.querySelector('p:nth-of-type(2)').innerText; // Correctly get the date
        const time = eventCard.querySelector('p:nth-of-type(3)').innerText; // Correctly get the time

        document.getElementById('edit-event-id').value = eventId;
        document.getElementById('edit-title').value = title;
        document.getElementById('edit-description').value = description;
        document.getElementById('edit-date').value = date;
        document.getElementById('edit-time').value = time;

        document.getElementById('edit-modal').style.display = 'block';
    } else if (e.target.classList.contains('delete-btn')) {
        const eventCard = e.target.closest('.event-card');
        const eventId = eventCard.getAttribute('data-id');

        // Implement AJAX request to delete the event from the database
        fetch(`delete-event.php?id=${eventId}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                eventCard.remove(); // Remove the event card from the UI
            } else {
                console.error("Error deleting event:", result.message);
            }
        })
        .catch(error => console.error("Error:", error));
    }
});

// Close modal functionality
document.querySelector('.close-btn').addEventListener('click', function() {
    document.getElementById('edit-modal').style.display = 'none';
});
