<div class="container">
    <form action="{$admin_uri}/user/submit" method="POST" class="form-horizontal" >
        <fieldset>
            <div id="legend">
                <legend class="">New user</legend>
            </div>

            <div class="control-group">
                <input type="hidden" name="type" value="user">
                <label class="control-label" for="firstName">First name</label>
                <div class="controls">
                    <input id="firstName" name="firstname" type="text" placeholder="First name" class="input-large">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="lastName">Last name</label>
                <div class="controls">
                    <input id="lastName" name="lastname" type="text" placeholder="Last name" class="input-large">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="email">Email</label>
                <div class="controls">
                    <input id="email" name="email" type="text" placeholder="example@email.com" class="input-large">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Department</label>
                <input type="hidden" name="department[type]" value="department" />
                <div class="controls">
                    {html_options name="department[id]" options=$depOptions}
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">Role</label>
                <input type="hidden" name="role[type]" value="role" />
                <div class="controls">
                    {html_options name="role[id]" options=$roleOptions}
                </div>
            </div>

            <div class="control-group">
                <label class="control-label">User level</label>
                <input type="hidden" name="userlevel[type]" value="userlevel" />
                <div class="controls">
                    {html_options name="userlevel[id]" options=$userLevelOptions|capitalize selected="3"}
                </div>
            </div>

            <input type="hidden" name="status" value="1" />

            <div class="control-group">
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Create user</button>
                    <button type="button" class="btn" onClick="history.go(-1);return true;">Cancel</button>
                </div>
            </div>

        </fieldset>
    </form>
</div>