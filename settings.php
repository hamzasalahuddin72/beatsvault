<div class="settings-wrapper">
    <div class="container settings-container">
        <div class="loading-box lb-settings">
            <img id="loading-bar" src="css/misc/img/loading-snake.gif">
            <br>
            <span style="text-align: center">{ Please wait.. }</span>
        </div>
        <div class="container-head">
            <div class="head-btn active-head" panel="0">Account</div>
            <div class="head-btn" panel="1">Preferences</div>
            <div class="head-btn" panel="2">Privacy</div>
            <div class="head-btn" panel="3">Security</div>
        </div>
        <div class="settings-body">
            <div class="settings-panel active-panel" panel="account">
                <div class="sub-panel sp-1">
                    <table>
                        <caption>Account details</caption>
                        <tr class="panel-row">
                            <th class="panel-item">Username</th>
                            <th class="panel-value" value="username"></th>
                        </tr>
                        <tr class="panel-row">
                            <th class="panel-item">Country</th>
                            <th class="panel-value" value="country"></th>
                        </tr>
                        <tr class="panel-row">
                            <th class="panel-item">First name</th>
                            <th class="panel-value" value="fname"></th>
                        </tr>
                        <tr class="panel-row">
                            <th class="panel-item">Last name</th>
                            <th class="panel-value" value="lname"></th>
                        </tr>
                        <tr class="panel-row">
                            <th class="panel-item">Email address</th>
                            <th class="panel-value" value="email"></th>
                        </tr>
                        <tr class="panel-row">
                            <th class="panel-item">Account type</th>
                            <th class="panel-value" value="acc-type"></th>
                        </tr>
                    </table>

                    <div class="music-preferences">
                        <div class="panel-list user-daws">
                            <span>Your DAWs</span>
                            <div class="panel-list ud-list"></div>
                        </div>
                        <div class="panel-list user-genres">
                            <span>Your genres</span>
                            <div class="panel-list ug-list"></div>
                        </div>
                    </div>
                </div>
                <div class="sub-panel sp-2">
                    <div class="panel-list user-instruments">
                        <span>Your instruments</span>
                        <div class="ui-list"></div>
                    </div>
                </div>
            </div>
            <div class="settings-panel" panel="preferences">
            </div>
            <div class="settings-panel" panel="privacy">
                <div class="sub-panel">
                    <table>
                        <tr class="panel-row">
                            <th class="panel-item">Public mode</th>
                            <th class="panel-value" value="public-mode">
                                <input class="panel-switch" type="checkbox" name="public-mode" value="1">
                            </th>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="settings-panel" panel="security">
            </div>
        </div>
    </div>
</div>