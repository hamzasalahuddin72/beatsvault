<div class="options-popup">
    <div class="head-btn popup-message-btn">
        <div class="icon-mask messages-btn"></div>
    </div>
    <div class="head-btn resources-view">
        <div class="icon-mask inventory-btn"></div>
    </div>
</div>
<div class="audio-player">
    <div id="media-info-display">
        <div id="audio-control-panel">
            <div class="controls">
                <div class="btn">
                    <div class="icon-mask master-previous-btn"></div>
                    <span class="tooltip">Previous</span>
                </div>
                <div class="btn">
                    <div class="icon-mask master-control master-play-btn"></div>
                    <span class="tooltip">Play</span>
                </div>

                <div class="btn">
                    <img class="icon-mask master-next-btn">
                    <span class="tooltip">Next</span>
                </div>

                <div class="btn">
                    <img class="icon-mask master-loop-btn">
                    <span class="tooltip">Loop</span>
                </div>
            </div>
            <div id="current-track-details">
            </div>
            <div id="duration-span">
            </div>
        </div>
        <div id="media-duration">
            <input type="range" id="audio-seek-slider" min="1" max="100" value="0">
            <div id="completion-bar"></div>
        </div>
    </div>
</div>