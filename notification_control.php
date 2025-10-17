<?php
session_start();
require_once 'db_connect.php';

// Check if the user is logged in AND is an admin
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !$_SESSION['is_admin']) {
    header('Location: login.php');
    exit;
}

// Fetch all contact messages that are not soft-deleted
$messages = [];
$sql_messages = "SELECT * FROM contact_messages WHERE deleted_at IS NULL ORDER BY created_at DESC";
if ($result = mysqli_query($link, $sql_messages)) {
    while ($row = mysqli_fetch_assoc($result)) {
        $messages[] = $row;
    }
}

// Fetch all testimonials that are not soft-deleted
$testimonials = [];
$sql_testimonials = "SELECT t.*, u.username FROM testimonials t JOIN users u ON t.user_id = u.user_id WHERE t.deleted_at IS NULL ORDER BY t.created_at DESC";
if ($result = mysqli_query($link, $sql_testimonials)) {
    while ($row = mysqli_fetch_assoc($result)) {
        $testimonials[] = $row;
    }
}

mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tavern Publico - Notification Control</title>
    <link rel="stylesheet" href="CSS/admin.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* --- ENHANCED TAB STYLING --- */
        .tabs-container {
            display: flex;
            border-bottom: 2px solid #ccc; 
            margin-bottom: 20px;
        }

        .tab-button {
            padding: 12px 22px;
            cursor: pointer;
            border: none;
            background-color: transparent;
            font-size: 16px;
            font-weight: 600;
            color: #555;
            text-decoration: none;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            margin-bottom: -2px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: color 0.3s, background-color 0.3s, transform 0.1s ease-out;
        }

        .tab-button:hover {
            background-color: #e9ecef;
            color: #2980b9;
        }

        .tab-button.active {
            background-color: #3498db;
            color: #fff;
        }
        
        .tab-content { display: none; }
        .tab-content.active { display: block; animation: fadeIn 0.5s; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        
        .reservation-page-header h2 {
            font-size: 24px;
            margin: 0;
            color: #333;
            font-weight: 600;
            flex-grow: 1; 
        }

        .truncate-text { display: block; max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        #readMoreBody { white-space: pre-wrap; text-align: left; background-color: #f5f5f5; padding: 15px; border-radius: 5px; max-height: 50vh; overflow-y: auto; line-height: 1.6; }
        
        .btn.view-full-text-btn { background-color: #007bff; color: white; }
        .btn.view-full-text-btn:hover { background-color: #0069d9; }
        .btn.reply-message-btn { background-color: #28a745; color: white; }
        .btn.reply-message-btn:hover { background-color: #218838; }
        .btn.delete-message-btn, .btn.delete-testimonial-btn { background-color: #dc3545; color: white; }
        .btn.delete-message-btn:hover, .btn.delete-testimonial-btn:hover { background-color: #c82333; }

        .btn.feature-btn[data-featured="1"] { background-color: #17a2b8; color: white; }
        .btn.feature-btn[data-featured="0"] { background-color: #6c757d; color: white; }

        #alertModal .modal-content { max-width: 450px; text-align: center; }
        #alertModal h2 { margin-top: 0; }
        #alertModalActions { justify-content: center; }

        .btn-loading {
            position: relative;
            color: transparent !important;
            cursor: wait;
            pointer-events: none;
        }
        .btn-loading::after {
            content: '';
            position: absolute;
            left: 50%;
            top: 50%;
            width: 20px;
            height: 20px;
            margin-left: -10px;
            margin-top: -10px;
            border: 3px solid rgba(255, 255, 255, 0.5);
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Pagination Styles */
        .pagination-container { display: flex; justify-content: center; align-items: center; margin-top: 25px; padding: 10px 0; gap: 10px; }
        #pageNumbersMessages, #pageNumbersTestimonials { display: flex; gap: 5px; }
        .page-number { padding: 8px 14px; border: 1px solid #ddd; border-radius: 4px; cursor: pointer; transition: background-color 0.2s, color 0.2s; background-color: #fff; color: #333; font-weight: 500; }
        .page-number:hover { background-color: #f0f0f0; }
        .page-number.active { background-color: #007bff; color: white; border-color: #007bff; font-weight: bold; }
        .pagination-container .btn:disabled { background-color: #e9ecef; color: #6c757d; cursor: not-allowed; opacity: 0.7; }
    </style>
</head>
<body>

    <div class="page-wrapper">
        <aside class="admin-sidebar">
            <div class="sidebar-header"> <img src="Tavern.png" alt="Home Icon" class="home-icon"> </div>
            <nav>
                 <ul class="sidebar-menu">
                    <li class="menu-item"><a href="admin.php"><i class="material-icons">dashboard</i> Dashboard</a></li>
                     <li class="menu-item"><a href="update.php"><i class="material-icons">file_upload</i> Upload Management</a></li>
                    <li class="menu-item"><a href="reservation.php"><i class="material-icons">event_note</i> Reservation</a></li>
                </ul>
                <div class="user-management-title">User Management</div>
                <ul class="sidebar-menu user-management-menu">
                    <li class="menu-item active"><a href="notification_control.php"><i class="material-icons">notifications</i> Notification Control</a></li>
                    <li class="menu-item"><a href="table_management.php"><i class="material-icons">table_chart</i>Calendar Management</a></li>
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
                    <h1 class="header-page-title">Notification Control</h1>
                    <div class="admin-header-right">
                        
                        <?php $admin_avatar_path = isset($_SESSION['avatar']) && file_exists($_SESSION['avatar']) ? htmlspecialchars($_SESSION['avatar']) : 'images/default_avatar.png'; ?>
                        <img src="<?php echo $admin_avatar_path; ?>" alt="Admin Avatar" class="admin-avatar">
                         <div class="admin-user-info">
                            <span class="admin-username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
           
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
                <div class="tabs-container">
                    <button class="tab-button active" onclick="openTab(event, 'messages')">
                        <i class="material-icons">email</i>
                        <span>Contact Messages</span>
                    </button>
                    <button class="tab-button" onclick="openTab(event, 'testimonials')">
                        <i class="material-icons">star_rate</i>
                        <span>Guest Testimonials</span>
                    </button>
                </div>

                <div id="messages" class="tab-content">
                    <div class="reservation-page-header">
                        <h2>Contact Form Messages</h2>
                        <input type="text" id="messageSearch" class="search-input" placeholder="Search messages...">
                    </div>
                    <section class="all-reservations-section">
                        <div class="table-responsive">
                            <table id="messagesTable">
                                <thead>
                                    <tr>
                                        <th>CUSTOMER</th> <th>SUBJECT</th> <th>MESSAGE</th> <th>RECEIVED</th> <th>STATUS</th> <th>ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($messages)): ?>
                                        <tr><td colspan="6" style="text-align: center;">No messages found.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($messages as $message): ?>
                                            <tr data-id="<?php echo $message['id']; ?>" 
                                                data-email="<?php echo htmlspecialchars($message['email']); ?>"
                                                data-subject="<?php echo htmlspecialchars($message['subject']); ?>"
                                                data-messagebody="<?php echo htmlspecialchars($message['message']); ?>">
                                                <td>
                                                    <strong><?php echo htmlspecialchars($message['name']); ?></strong><br>
                                                    <small><?php echo htmlspecialchars($message['email']); ?></small>
                                                </td>
                                                <td><?php echo htmlspecialchars($message['subject']); ?></td>
                                                <td><span class="truncate-text" title="<?php echo htmlspecialchars($message['message']); ?>"><?php echo htmlspecialchars($message['message']); ?></span></td>
                                                <td><?php echo htmlspecialchars($message['created_at']); ?></td>
                                                <td><span class="status-badge <?php echo !empty($message['replied_at']) ? 'confirmed' : 'pending'; ?>"><?php echo !empty($message['replied_at']) ? 'Replied' : 'New'; ?></span></td>
                                                <td class="actions">
                                                    <button class="btn btn-small view-full-text-btn">View</button>
                                                    <button class="btn btn-small reply-message-btn">Reply</button>
                                                    <button class="btn btn-small delete-message-btn">Delete</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="pagination-container" id="paginationMessages">
                            <button class="btn" id="prevPageMessages" disabled>&laquo; Previous</button>
                            <div id="pageNumbersMessages"></div>
                            <button class="btn" id="nextPageMessages">Next &raquo;</button>
                        </div>
                    </section>
                </div>

                <div id="testimonials" class="tab-content">
                    <div class="reservation-page-header">
                        <h2>Guest Testimonials</h2>
                        <input type="text" id="testimonialSearch" class="search-input" placeholder="Search testimonials...">
                    </div>
                    <section class="all-reservations-section">
                        <div class="table-responsive">
                            <table id="testimonialsTable">
                                <thead>
                                    <tr>
                                        <th>USERNAME</th> <th>RATING</th> <th>COMMENT</th> <th>FEATURED</th> <th>ACTIONS</th>
                                    </tr>
                                </thead>
                                <tbody id="testimonialsTableBody">
                                    <?php if (empty($testimonials)): ?>
                                        <tr><td colspan="5" style="text-align: center;">No testimonials found.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($testimonials as $testimonial): ?>
                                            <tr data-id="<?php echo $testimonial['id']; ?>" 
                                                data-comment="<?php echo htmlspecialchars($testimonial['comment'], ENT_QUOTES); ?>" 
                                                data-username="<?php echo htmlspecialchars($testimonial['username'], ENT_QUOTES); ?>">
                                                <td><?php echo htmlspecialchars($testimonial['username']); ?></td>
                                                <td><?php echo str_repeat('★', $testimonial['rating']) . str_repeat('☆', 5 - $testimonial['rating']); ?></td>
                                                <td><span class="truncate-text" title="<?php echo htmlspecialchars($testimonial['comment']); ?>"><?php echo htmlspecialchars($testimonial['comment']); ?></span></td>
                                                <td>
                                                    <button class="btn btn-small feature-btn" data-featured="<?php echo $testimonial['is_featured']; ?>">
                                                        <?php echo $testimonial['is_featured'] ? 'Yes' : 'No'; ?>
                                                    </button>
                                                </td>
                                                <td class="actions">
                                                    <button class="btn btn-small view-full-text-btn">View</button>
                                                    <button class="btn btn-small delete-testimonial-btn">Delete</button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                         <div class="pagination-container" id="paginationTestimonials">
                            <button class="btn" id="prevPageTestimonials" disabled>&laquo; Previous</button>
                            <div id="pageNumbersTestimonials"></div>
                            <button class="btn" id="nextPageTestimonials">Next &raquo;</button>
                        </div>
                    </section>
                </div>
            </main>
        </div>
    </div>

    <div id="replyModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2>Reply to Message</h2>
            <form id="replyMessageForm">
                <input type="hidden" id="replyMessageId" name="message_id">
                <input type="hidden" id="replyCustomerEmail" name="customer_email">
                <div class="form-group"><label>Original Message:</label><div id="originalMessage" style="background-color: #f0f0f0; padding: 10px; border-radius: 5px; min-height: 80px;"></div></div>
                <div class="form-group"><label for="replyText">Your Reply:</label><textarea id="replyText" name="reply_text" rows="6" required></textarea></div>
                <div class="modal-actions"><button type="submit" class="btn modal-save-btn">Send Reply</button></div>
            </form>
        </div>
    </div>
    
    <div id="readMoreModal" class="modal">
        <div class="modal-content"><span class="close-button">&times;</span><h2 id="readMoreTitle">Full Text</h2><p id="readMoreBody"></p></div>
    </div>

    <div id="alertModal" class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <h2 id="alertModalTitle" style="margin-top: 0;"></h2>
            <p id="alertModalMessage"></p>
            <div id="alertModalActions" class="modal-actions"></div>
        </div>
    </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
            tabcontent[i].classList.remove("active");
        }
        tablinks = document.getElementsByClassName("tab-button");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(tabName).style.display = "block";
        document.getElementById(tabName).classList.add("active");
        evt.currentTarget.className += " active";

        // Re-initialize pagination for the active tab
        if (tabName === 'messages') {
            initializePagination('messages');
        } else if (tabName === 'testimonials') {
            initializePagination('testimonials');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelector('.tab-button.active').click();

        // --- NEW MODAL FUNCTIONS ---
        const alertModal = $('#alertModal');
        const alertModalTitle = $('#alertModalTitle');
        const alertModalMessage = $('#alertModalMessage');
        const alertModalActions = $('#alertModalActions');

        function showAlert(title, message, callback) {
            alertModalTitle.text(title);
            alertModalMessage.text(message);
            alertModalActions.html('<button class="btn" id="alertOkBtn">OK</button>');
            alertModal.css('display', 'flex');
            $('#alertOkBtn').on('click', function() {
                alertModal.css('display', 'none');
                if (callback) callback();
            });
        }

        function showConfirm(title, message, callback) {
            alertModalTitle.text(title);
            alertModalMessage.text(message);
            alertModalActions.html(
                '<button class="btn" id="confirmCancelBtn" style="background-color: #6c757d; color: white;">Cancel</button>' +
                '<button class="btn" id="confirmOkBtn" style="background-color: #dc3545; color: white;">Yes, Proceed</button>'
            );
            alertModal.css('display', 'flex');

            $('#confirmOkBtn').off('click').on('click', function() {
                alertModal.css('display', 'none');
                callback(true);
            });
            $('#confirmCancelBtn').off('click').on('click', function() {
                alertModal.css('display', 'none');
                callback(false);
            });
        }

        alertModal.on('click', '.close-button', function() {
            alertModal.css('display', 'none');
        });
        
        $(window).on('click', function(event) {
            if ($(event.target).is(alertModal)) {
                alertModal.css('display', 'none');
            }
        });

        // --- Testimonials Actions ---
        $('#testimonialsTableBody').on('click', '.feature-btn', function() {
            var btn = $(this);
            var testimonialId = btn.closest('tr').data('id');
            var isCurrentlyFeatured = btn.data('featured') == 1;
            var actionText = isCurrentlyFeatured ? 'un-feature' : 'feature';

            showConfirm('Confirm Action', `Are you sure you want to ${actionText} this testimonial?`, function(confirmed) {
                if (confirmed) {
                    $.ajax({
                        url: 'manage_testimonial.php',
                        type: 'POST',
                        data: { action: 'feature', testimonial_id: testimonialId },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                var isFeatured = btn.data('featured') == 1;
                                btn.data('featured', isFeatured ? 0 : 1);
                                btn.attr('data-featured', isFeatured ? 0 : 1);
                                btn.text(isFeatured ? 'No' : 'Yes');
                            } else {
                                showAlert('Error', response.message);
                            }
                        },
                        error: function() {
                            showAlert('Error', 'An unexpected server error occurred.');
                        }
                    });
                }
            });
        });

        $('#testimonialsTableBody').on('click', '.delete-testimonial-btn', function() {
            var row = $(this).closest('tr');
            var testimonialId = row.data('id');
            showConfirm('Confirm Deletion', 'Are you sure you want to move this testimonial to the deletion history?', function(confirmed) {
                if (confirmed) {
                    $.ajax({
                        url: 'manage_testimonial.php',
                        type: 'POST',
                        data: { action: 'delete', testimonial_id: testimonialId },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                row.fadeOut(400, function() { 
                                    $(this).remove();
                                    initializePagination('testimonials'); // Re-init pagination after deletion
                                });
                            } else {
                                showAlert('Error', response.message);
                            }
                        }
                    });
                }
            });
        });
        
        // --- Messages Actions ---
        const replyModal = document.getElementById('replyModal');
        const replyCloseBtn = replyModal.querySelector('.close-button');

        $('#messagesTable').on('click', '.reply-message-btn', function() {
            var row = $(this).closest('tr');
            $('#replyMessageId').val(row.data('id'));
            $('#replyCustomerEmail').val(row.data('email'));
            $('#originalMessage').text(row.data('messagebody'));
            replyModal.style.display = 'flex';
        });

        $('#messagesTable').on('click', '.delete-message-btn', function() {
            var row = $(this).closest('tr');
            var messageId = row.data('id');
            showConfirm('Confirm Deletion', 'Are you sure you want to move this message to the deletion history?', function(confirmed) {
                if (confirmed) {
                    $.ajax({
                        url: 'manage_message.php',
                        type: 'POST',
                        data: { action: 'delete', message_id: messageId },
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                               row.fadeOut(400, function() { 
                                   $(this).remove(); 
                                   initializePagination('messages'); // Re-init pagination after deletion
                               });
                            } else {
                               showAlert('Error', response.message);
                            }
                        }
                    });
                }
            });
        });

        replyCloseBtn.onclick = function() { replyModal.style.display = 'none'; }

        $('#replyMessageForm').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var submitBtn = form.find('button[type="submit"]');
            var formData = form.serialize() + '&action=reply';
            
            submitBtn.addClass('btn-loading');

            $.ajax({
                url: 'manage_message.php', 
                type: 'POST', 
                data: formData, 
                dataType: 'json',
                success: function(response) {
                    showAlert(response.success ? 'Success' : 'Error', response.message, function() {
                        if (response.success) {
                            replyModal.style.display = 'none';
                            location.reload();
                        }
                    });
                },
                error: function() {
                    showAlert('Error', 'An unexpected server error occurred.');
                },
                complete: function() {
                    submitBtn.removeClass('btn-loading');
                }
            });
        });

        const readMoreModal = document.getElementById('readMoreModal');
        const readMoreTitle = document.getElementById('readMoreTitle');
        const readMoreBody = document.getElementById('readMoreBody');
        const readMoreCloseBtn = readMoreModal.querySelector('.close-button');

        function openReadMoreModal(title, content) {
            readMoreTitle.textContent = title;
            readMoreBody.textContent = content;
            readMoreModal.style.display = 'flex';
        }

        readMoreCloseBtn.addEventListener('click', () => { readMoreModal.style.display = 'none'; });
        
        document.body.addEventListener('click', function(e) {
            if (e.target.classList.contains('view-full-text-btn')) {
                const row = e.target.closest('tr');
                if (row) {
                    let title = 'Full Message';
                    let content = '';
                    if ($(row).closest('#messagesTable').length) {
                        const customerName = row.querySelector('strong').textContent;
                        title = `Message from ${customerName}`;
                        content = row.dataset.messagebody;
                    } else if ($(row).closest('#testimonialsTable').length) {
                        title = `Comment from ${row.dataset.username}`;
                        content = row.dataset.comment;
                    }
                    openReadMoreModal(title, content);
                }
            }
        });

        window.onclick = function(event) {
            if (event.target == replyModal) { replyModal.style.display = 'none'; }
            if (event.target == readMoreModal) { readMoreModal.style.display = 'none'; }
        }

        // --- PAGINATION AND SEARCH LOGIC ---
        const paginationConfig = {
            messages: {
                tableBody: document.getElementById('messagesTable').querySelector('tbody'),
                searchInput: document.getElementById('messageSearch'),
                paginationContainer: document.getElementById('paginationMessages'),
                prevPageBtn: document.getElementById('prevPageMessages'),
                nextPageBtn: document.getElementById('nextPageMessages'),
                pageNumbersContainer: document.getElementById('pageNumbersMessages'),
                rowsPerPage: 8,
                allRows: [],
                filteredRows: [],
                currentPage: 1
            },
            testimonials: {
                tableBody: document.getElementById('testimonialsTableBody'),
                searchInput: document.getElementById('testimonialSearch'),
                paginationContainer: document.getElementById('paginationTestimonials'),
                prevPageBtn: document.getElementById('prevPageTestimonials'),
                nextPageBtn: document.getElementById('nextPageTestimonials'),
                pageNumbersContainer: document.getElementById('pageNumbersTestimonials'),
                rowsPerPage: 8,
                allRows: [],
                filteredRows: [],
                currentPage: 1
            }
        };

        function initializePagination(key) {
            const config = paginationConfig[key];
            config.allRows = Array.from(config.tableBody.querySelectorAll('tr'));
            config.filteredRows = config.allRows;

            if (config.allRows.length <= config.rowsPerPage) {
                config.paginationContainer.style.display = 'none';
            } else {
                config.paginationContainer.style.display = 'flex';
            }

            config.searchInput.addEventListener('keyup', () => {
                const filter = config.searchInput.value.toLowerCase();
                config.filteredRows = config.allRows.filter(row => {
                    const rowText = row.textContent.toLowerCase();
                    return rowText.includes(filter);
                });
                displayPage(key, 1);
            });
            
            config.prevPageBtn.addEventListener('click', () => {
                if (config.currentPage > 1) displayPage(key, config.currentPage - 1);
            });
            
            config.nextPageBtn.addEventListener('click', () => {
                const pageCount = Math.ceil(config.filteredRows.length / config.rowsPerPage);
                if (config.currentPage < pageCount) displayPage(key, config.currentPage + 1);
            });
            
            displayPage(key, 1);
        }

        function displayPage(key, page) {
            const config = paginationConfig[key];
            config.currentPage = page;
            
            config.tableBody.innerHTML = '';

            const start = (page - 1) * config.rowsPerPage;
            const end = start + config.rowsPerPage;
            const paginatedItems = config.filteredRows.slice(start, end);

            paginatedItems.forEach(row => {
                config.tableBody.appendChild(row);
            });
            
            updatePaginationUI(key);
        }

        function updatePaginationUI(key) {
            const config = paginationConfig[key];
            const pageCount = Math.ceil(config.filteredRows.length / config.rowsPerPage);
            
             if (pageCount <= 1) {
                config.paginationContainer.style.display = 'none';
                return;
            }
            config.paginationContainer.style.display = 'flex';

            config.prevPageBtn.disabled = config.currentPage === 1;
            config.nextPageBtn.disabled = config.currentPage === pageCount;

            config.pageNumbersContainer.innerHTML = '';
            for (let i = 1; i <= pageCount; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.textContent = i;
                pageBtn.className = 'page-number' + (i === config.currentPage ? ' active' : '');
                pageBtn.addEventListener('click', () => displayPage(key, i));
                config.pageNumbersContainer.appendChild(pageBtn);
            }
        }
    });
</script>
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