{include file="lib/functions.tpl"}

<script type="text/javascript">
    var base_uri = '{$smarty.const.BASE_URI}';
</script>

{function print_user_info}
    <tr>
        <td>{$user.firstname|capitalize:true}</td>
        <td>{$user.lastname}</td>
        <td>{$user.email}</td>
        <td>{$user.department.name|capitalize:true}</td>
        <td>{$user.role.name|capitalize:true}</td>
        <td>{$user.userlevel.name|capitalize}</td>
        <td>
        {if $user.status == 0}
            {t}No{/t}
        {elseif $user.status == 1}
            {t}Yes{/t}
        {/if}
        </td>
        {call print_actions type="user" level="admin"}
    </tr>
{/function}

{if !empty($users)}
<table class="table table-striped">
    <thead>
        <tr>
            <th>{t}First name{/t}</th>
            <th>{t}Last name{/t}</th>
            <th>{t}Email{/t}</th>
            <th>{t}Department{/t}</th>
            <th>{t}Role{/t}</th>
            <th>{t}User level{/t}</th>
            <th>
                {t}Included? {/t}
                <span>
                    <i class="icon-question-sign" data-toggle="tooltip" title="{t}This column shows if a person is included in the feedback round.{/t}" data-placement="right"></i>
                </span>
            </th>
            <th>{t}Actions{/t}</th>
        </tr>
    </thead>
    <tbody>
    {foreach $users as $user}
        {print_user_info}
    {/foreach}
    </tbody>
</table>
{else}
    {t}No users found!{/t}
{/if}

{call print_add_link level="admin" type_var="user" type="{t}user{/t}"}

<script type="text/javascript">
    $(document).ready(function()
    {
        var ignored_columns = [-1, -2]; // Stop the reports and actions columns from being sortable
        create_datatable($('.table.table-striped'), ignored_columns);
    });
</script>
