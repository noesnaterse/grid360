<div class="page-header"><h1>Feedback overview</h1></div>
<div class="row-fluid">
<span class="span6">
        {if array_key_exists($currentUser.id, $roundinfo)}
            {if $roundinfo[$currentUser.id].status == 0}
                <div class="alert alert-info">
                    You have not reviewed yourself yet! <a href="{$BASE_URI}feedback/{$currentUser.id}">Click here to review yourself</a>
                </div>
            {/if}
        {else}
            <div class="alert alert-info">
                You have not reviewed yourself yet! <a href="{$BASE_URI}feedback/{$currentUser.id}">Click here to review yourself</a>
            </div>
        {/if}

    {foreach $roundinfo as $info}
        {if $info.reviewee.id != $currentUser.id && $info.status == 0}
            <div class="alert alert-info">
                You have pending reviews! Click the review button next to an open review to review that person.
            </div>
            {break}
        {/if}
    {/foreach}

    <table class="table table-striped">
        <thead>
        <tr>
            <th>Reviewee name</th>
            <th>Status</th>
        </tr>
        </thead>
        <tbody>
        {foreach $roundinfo as $info}
            {if $info.reviewee.id != $currentUser.id}
                <tr>
                    <td>{$info.reviewee.firstname} {$info.reviewee.lastname}</td>
                    <td>
                        {if $info.status == 0}
                            Pending
                        {else}
                            Completed
                        {/if}
                    </td>
                    <td>
                        {if $info.status == 0}
                            <a href="{$BASE_URI}feedback/{$info.reviewee.id}">Review</a>
                        {/if}
                    </td>
                </tr>
            {/if}
        {/foreach}
        </tbody>
    </table>
    </span>
</div>
