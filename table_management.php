<?php
session_start();
require_once 'db_connect.php';

// Check if the user is logged in AND is an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

// Fetch blocked dates for display
$blocked_dates_list = [];
$sql_blocked_list = "SELECT id, block_date FROM blocked_dates ORDER BY block_date DESC"; // Order by DESC to show newest first
if ($result = mysqli_query($link, $sql_blocked_list)) {
    while ($row = mysqli_fetch_assoc($result)) {
        $blocked_dates_list[] = $row;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tavern Publico - Table Management</title>
    <link rel="stylesheet" href="CSS/admin.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
    <style>
        .management-grid {
            display: grid;
            grid-template-columns: 1fr 1.5fr; /* Adjust column ratio */
            gap: 20px;
            margin-bottom: 20px;
        }
        .block-date-form, .blocked-dates-list {
            background-color: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.07);
        }
        .block-date-form h3, .blocked-dates-list h3 {
             margin-top: 0;
             color: #34495e;
             font-size: 1.2em;
             border-bottom: 1px solid #eee;
             padding-bottom: 10px;
        }
        #blocked-dates-container {
            max-height: 220px;
            overflow-y: auto;
            padding-right: 10px;
        }
        #blocked-dates-container::-webkit-scrollbar { width: 8px; }
        #blocked-dates-container::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        #blocked-dates-container::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
        #blocked-dates-container::-webkit-scrollbar-thumb:hover { background: #aaa; }
        .blocked-date-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 15px;
            border-radius: 8px;
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            margin-bottom: 10px;
            transition: background-color 0.3s;
        }
        .blocked-date-item:hover { background-color: #e9ecef; }
        .blocked-date-item span { font-weight: 500; color: #34495e; }
        .unblock-date-btn { background-color: #e74c3c; color: white; }
        .unblock-date-btn:hover { background-color: #c0392b; }
        .calendar-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.07);
            cursor: pointer;
        }
        #dateDetailsModal .modal-content { max-width: 700px; }
        #dateDetailsModal table { width: 100%; margin-top: 15px; border-collapse: collapse; }
        #dateDetailsModal th, #dateDetailsModal td { padding: 10px 15px; text-align: left; border-bottom: 1px solid #eee; }
        #dateDetailsModal th { background-color: #f8f9fa; }
        
        #notificationModal .modal-content { text-align: center; }
        #notificationModal .modal-header-icon { font-size: 4em; margin-bottom: 15px; }
        #notificationModal .modal-header-icon.success { color: #28a745; }
        #notificationModal .modal-header-icon.error { color: #dc3545; }

        @media (max-width: 992px) { .management-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

    <div class="page-wrapper">
        <aside class="admin-sidebar">
             <div class="sidebar-header"><img src="Tavern.png" alt="Home Icon" class="home-icon"></div>
            <nav>
                 <ul class="sidebar-menu">
                    <li class="menu-item"><a href="admin.php"><i class="material-icons">dashboard</i> Dashboard</a></li>
                     <li class="menu-item"><a href="update.php"><i class="material-icons">file_upload</i> Upload Management</a></li>
                    <li class="menu-item"><a href="reservation.php"><i class="material-icons">event_note</i> Reservation</a></li>
                </ul>
                <div class="user-management-title">User Management</div>
                <ul class="sidebar-menu user-management-menu">
                   <li class="menu-item"><a href="notification_control.php"><i class="material-icons">notifications</i> Notification Control</a></li>
                    <li class="menu-item active"><a href="table_management.php"><i class="material-icons">table_chart</i>Calendar management</a></li>
                    <li class="menu-item"><a href="customer_database.php"><i class="material-icons">people</i> Customer Database</a></li>
                    <li class="menu-item"><a href="reports.php"><i class="material-icons">analytics</i>Reservation Reports</a></li>
                    <li class="menu-item"><a href="deletion_history.php"><i class="material-icons">history</i>Archive</a></li>
                    <li class="menu-item"><a href="logout.php"><i class="material-icons">logout</i> Log out</a></li>
                </ul>
            </nav>
        </aside>

        <div class="admin-content-area">
            <header class="main-header">
                <div class="header-content">
                    <h1 class="header-page-title">Availability Management</h1>
                    <div class="admin-header-right">

                        <?php $admin_avatar_path = isset($_SESSION['avatar']) && file_exists($_SESSION['avatar']) ? htmlspecialchars($_SESSION['avatar']) : 'images/default_avatar.png'; ?>
                        <img src="<?php echo $admin_avatar_path; ?>" alt="Admin Avatar" class="admin-avatar">
                         <div class="admin-user-info">
                            <span class="admin-username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                            <span class="admin-role"></span>
                        </div>
                        
                        <div class="admin-notification-area">
                            <div class="admin-notification-item">
                                <button class="admin-notification-button" id="adminMessageBtn" title="Messages">
                                    <i class="material-icons">email</i>
                                    <span class="admin-notification-badge" id="adminMessageCount" style="display: none;">0</span>
                                </button>
                                <div class="admin-notification-dropdown" id="adminMessageDropdown"></div>
                            </div>
                            <div class="admin-notification-item">
                                <button class="admin-notification-button" id="adminReservationBtn" title="Reservations">
                                    <i class="material-icons">event_note</i>
                                    <span class="admin-notification-badge" id="adminReservationCount" style="display: none;">0</span>
                                </button>
                                <div class="admin-notification-dropdown" id="adminReservationDropdown"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </header>

            <main class="dashboard-main-content">
                <div class="management-grid">
                    <div class="block-date-form">
                        <h3><i class="material-icons" style="vertical-align: middle; margin-right: 8px;">block</i>Block Reservations</h3>
                        <form id="blockDateForm">
                            <div class="form-group">
                                <label for="block_date_start">Start Date:</label>
                                <input type="date" id="block_date_start" name="block_date_start" min="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="block_date_end">End Date (Optional):</label>
                                <input type="date" id="block_date_end" name="block_date_end" min="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <button type="submit" class="btn btn-primary" style="background-color:#dc3545; width: 100%;">Block Date(s)</button>
                        </form>
                    </div>
                    <div class="blocked-dates-list">
                        <h3><i class="material-icons" style="vertical-align: middle; margin-right: 8px;">event_busy</i>Currently Blocked Dates</h3>
                        <div id="blocked-dates-container">
                            <?php if (empty($blocked_dates_list)): ?>
                                <p>No dates are currently blocked.</p>
                            <?php else: ?>
                                <?php foreach ($blocked_dates_list as $date): ?>
                                    <div class="blocked-date-item" data-id="<?php echo $date['id']; ?>">
                                        <span><?php echo htmlspecialchars(date('F j, Y', strtotime($date['block_date']))); ?></span>
                                        <button class="btn btn-small unblock-date-btn">Unblock</button>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="calendar-container"><div id="calendar"></div></div>
            </main>
        </div>
    </div>

    <div id="dateDetailsModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2 id="modalDateTitle">Reservations</h2>
            <div id="modalReservationsList" class="table-responsive"></div>
        </div>
    </div>

    <div id="notificationModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <div id="modalHeaderIcon" class="modal-header-icon"></div>
            <h2 id="modalTitle"></h2>
            <p id="modalMessage"></p>
            <button class="btn modal-close-btn" style="background-color: #34495e; color: white;">OK</button>
        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    
    <script src="JS/table_management.js"></script>
    
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const messageBtn = document.getElementById('adminMessageBtn');
        const reservationBtn = document.getElementById('adminReservationBtn');
        const messageDropdown = document.getElementById('adminMessageDropdown');
        const reservationDropdown = document.getElementById('adminReservationDropdown');
        
        const messageCountBadge = document.getElementById('adminMessageCount');
        const reservationCountBadge = document.getElementById('adminReservationCount');

        async function fetchAdminNotifications() {
            try {
                const response = await fetch('get_admin_notifications.php');
                const data = await response.json();

                if (data.success) {
                    // Update Message Count and Dropdown
                    if (data.new_messages > 0) {
                        messageCountBadge.textContent = data.new_messages;
                        messageCountBadge.style.display = 'block';
                    } else {
                        messageCountBadge.style.display = 'none';
                    }
                    messageDropdown.innerHTML = data.messages_html;

                    // Update Reservation Count and Dropdown
                    if (data.pending_reservations > 0) {
                        reservationCountBadge.textContent = data.pending_reservations;
                        reservationCountBadge.style.display = 'block';
                    } else {
                        reservationCountBadge.style.display = 'none';
                    }
                    reservationDropdown.innerHTML = data.reservations_html;
                }
            } catch (error) {
                console.error('Error fetching admin notifications:', error);
            }
        }

        // Toggle dropdowns
        messageBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            reservationDropdown.classList.remove('show');
            messageDropdown.classList.toggle('show');
        });

        reservationBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            messageDropdown.classList.remove('show');
            reservationDropdown.classList.toggle('show');
        });

        // Close dropdowns when clicking outside
        window.addEventListener('click', () => {
            messageDropdown.classList.remove('show');
            reservationDropdown.classList.remove('show');
        });
        
        // Prevent dropdown from closing when clicking inside link area
        [messageDropdown, reservationDropdown].forEach(dropdown => {
            dropdown.addEventListener('click', (e) => {
                // Only stop propagation if it's NOT the dismiss button
                if (!e.target.classList.contains('admin-notification-dismiss')) {
                    e.stopPropagation();
                }
            });
        });

        // --- Handle Dismiss Click ---
        async function handleDismiss(e) {
            if (!e.target.classList.contains('admin-notification-dismiss')) return;

            e.preventDefault(); // Prevent default button action
            e.stopPropagation(); // Stop event from bubbling up and closing dropdown

            const button = e.target;
            const id = button.dataset.id;
            const type = button.dataset.type;
            const itemWrapper = button.parentElement;
            
            const formData = new FormData();
            formData.append('id', id);
            formData.append('type', type);

            try {
                const response = await fetch('clear_admin_notification.php', { method: 'POST', body: formData });
                const result = await response.json();

                if (result.success) {
                    // Visually remove the item
                    itemWrapper.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                    itemWrapper.style.opacity = '0';
                    itemWrapper.style.transform = 'translateX(-20px)';
                    setTimeout(() => {
                        itemWrapper.remove();
                        // Refetch to update counts and check if dropdown should be empty
                        fetchAdminNotifications(); 
                    }, 300);
                } else {
                    alert(result.message); // Show alert for actions that can't be dismissed
                }
            } catch (error) {
                console.error('Error dismissing notification:', error);
                alert('An error occurred. Please try again.');
            }
        }

        messageDropdown.addEventListener('click', handleDismiss);
        reservationDropdown.addEventListener('click', handleDismiss);

        // Initial fetch and polling
        fetchAdminNotifications();
        setInterval(fetchAdminNotifications, 30000); // Check for new notifications every 30 seconds
    });
    </script>
</body>
</html>