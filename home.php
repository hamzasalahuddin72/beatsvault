<div class="loading-box lb-home-feed">
    <img id="loading-bar" src="css/misc/img/loading-snake.gif">
    <br>
    <span style="text-align: center">{ Please wait.. }</span>
</div>
<div class="metadata-tray">
    <div class="meta-tray-bg-cover"></div>
</div>
<div class="sub-wrapper">
    <div class="home-feed-content">
    </div>
    <div class="sub-wrapper-sticky">
        <div class="container side-bar">
            <?php include("templates/messenger.php") ?>
            <div id="producers-mini-vault"></div>
            <div class="chat-container">
                <div class="chat-container-head">
                </div>
                <div class="chat-container-body">
                </div>
                <div class="chat-container-foot">
                    <textarea type="text" name="message-input" class="message-input"
                        placeholder="type your message..."></textarea>
                    <button class="message-send-btn">send</button>
                </div>
            </div>
        </div>
        <div class="container user-list">
            <div class="container-head">
                <span>Suggested users</span>
            </div>
            <div class="user-list-body">
            </div>
        </div>
    </div>
</div>