<main id="container">
    <h3>Send Notification</h3>

    <div id="form-block-center">
        <form action="processes/process.notifications.php" method="POST">
            <!-- Hidden input to specify the action -->
            <input type="hidden" name="action" value="send">
            
            <label for="recipient">Send To:</label>
            <select class="input" id="recipient" name="recipient" required>
                <option value="all">All Users</option>
                <option value="specific">Specific User</option>
            </select>

            <div id="username-block" style="display: none;">
                <label for="username">Username:</label>
                <input class="input" type="text" id="username" name="username" placeholder="Enter Username">
            </div>

            <label for="message">Message:</label>
            <textarea class="input" id="message" name="message" rows="4" placeholder="Enter your message" required></textarea>

            <input type="submit" value="Send Notification">
        </form>
    </div>
</main>

<script>
    // JavaScript to toggle username input based on selection
    const recipientSelect = document.getElementById('recipient');
    const usernameBlock = document.getElementById('username-block');

    recipientSelect.addEventListener('change', function() {
        if (this.value === 'specific') {
            usernameBlock.style.display = 'block';
        } else {
            usernameBlock.style.display = 'none';
        }
    });
</script>
