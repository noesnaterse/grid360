<?php

function create_role()
{
    security_authorize(MANAGER);

    $departments = get_departments_assoc();
    $competencygroups = get_competencygroups_assoc();

    global $smarty;
    $smarty->assign('department_options', $departments);
    $smarty->assign('competencygroup_options', $competencygroups);

    return html($smarty->fetch('role/role.tpl'));
}

function create_role_post()
{
    security_authorize(MANAGER);

    $keys_to_check = array('name' => $_POST['name'],
        'department' => array('load_bean' => true, 'id' => $_POST['department']['id'], 'type' => 'department'),
        'competencygroup' => array('load_bean' => true, 'id' => $_POST['competencygroup']['id'], 'type' => 'competencygroup'));

    $id = params('id');

    if(isset($id) && !empty($id))
    {
        $_POST['id'] = $id;
        $keys_to_check['id'] = array('load_bean' => true, 'id' => $_POST['id'], 'type' => 'role');
    }

    $form_values = validate_form($keys_to_check);

    global $smarty;

    $has_errors = false;
    foreach($form_values as $form_value)
    {
        if(isset($form_value['error']))
        {
            $has_errors = true;
            break;
        }
    }

    // Set the form values so they can be used again in the form
    if($has_errors || isset($form_values['error']))
    {
        $smarty->assign('form_values', $form_values);

        if(isset($_POST['id']))
        {
            return edit_role();
        }

        return create_role();
    }

    $role = R::graph($_POST);

    // Check if the department is a new department
    if($role->id != 0)
    {
        $message = sprintf(UPDATE_SUCCESS, _('role'), $role->name);
    }
    else
    {
        $message = sprintf(CREATE_SUCCESS, _('role'), $role->name);
    }

    R::store($role);

    header('Location: ' . MANAGER_URI . 'roles?success=' . $message);
}

function view_roles()
{
    security_authorize(MANAGER);

    global $smarty;

    if($_SESSION['current_user']->userlevel->level == ADMIN)
    {
        $roles = R::findAll('role');
    }
    else
    {
        $roles = R::find('role', 'department_id = ?', array($_SESSION['current_user']->department->id));
    }
    $smarty->assign('roles', $roles);
    $smarty->assign('page_header', _('Roles'));

    return html($smarty->fetch('role/roles.tpl'));
}

function edit_role()
{
    security_authorize(MANAGER);

    if($_SESSION['current_user']->userlevel->level == ADMIN)
    {
        $role = R::load('role', params('id'));
    }
    else
    {
        $role = R::findOne('role', 'department_id = ? AND id = ?', array($_SESSION['current_user']->department->id, params('id')));
    }

    if($role->id == 0)
    {
        header('Location: ' . ADMIN_URI . 'roles?error=' . sprintf(BEAN_NOT_FOUND, _('role')));
        exit;
    }

    $departments = get_departments_assoc();
    $competencygroups = get_competencygroups_assoc();

    global $smarty;

    if(!$smarty->getTemplateVars('form_values'))
    {
        $form_values = array();
        $form_values['id']['value'] = $role->id;
        $form_values['name']['value'] = $role->name;
        $form_values['description']['value'] = $role->description;
        $form_values['department']['value'] = $role->department->id;
        $form_values['competencygroup']['value'] = $role->competencygroup->id;

        $smarty->assign('form_values', $form_values);
    }

    $smarty->assign('role_name', $role->name);
    $smarty->assign('department_options', $departments);
    $smarty->assign('competencygroup_options', $competencygroups);
    $smarty->assign('update', true);

    return html($smarty->fetch('role/role.tpl'));
}

function delete_role_confirmation()
{
    security_authorize(MANAGER);

    if($_SESSION['current_user']->userlevel->level == ADMIN)
    {
        $role = R::load('role', params('id'));
    }
    else
    {
        $role = R::findOne('role', 'department_id = ? AND id = ?', array($_SESSION['current_user']->department->id, params('id')));
    }

    if($role->id == 0)
    {
        return html('Role not found!');
    }

    global $smarty;
    $smarty->assign('type', _('role'));
    $smarty->assign('type_var', 'role');
    $smarty->assign('role', $role);
    $smarty->assign('level_uri', MANAGER_URI);

    return html($smarty->fetch('common/delete_confirmation.tpl'));
}

function delete_role()
{
    security_authorize(MANAGER);

    if($_SESSION['current_user']->userlevel->level == ADMIN)
    {
        $role = R::load('role', params('id'));
    }
    else
    {
        $role = R::findOne('role', 'department_id = ? AND id = ?', array($_SESSION['current_user']->department->id, params('id')));
    }

    if($role->id == 0)
    {
        return html('Role not found!');
    }

    R::trash($role);

    $message = sprintf(DELETE_SUCCESS, _('role'), $role->name);

    header('Location: ' . MANAGER_URI . 'roles?success=' . $message);
    exit;
}

function validate_role_form()
{
    $form_values = array();

    $form_values['id']['value'] = $_POST['id'];
    $form_values['name']['value'] = $_POST['name'];
    $form_values['manager']['value'] = $_POST['user']['id'];
    $form_values['roles']['value'] = $_POST['ownRole'];

    if(!isset($_POST['type']) || $_POST['type'] != 'role')
    {
        $form_values['error'] = _('Error creating role');
    }
    if(!isset($_POST['name']) || strlen(trim($_POST['name'])) == 0)
    {
        $form_values['name']['error'] = sprintf(FIELD_REQUIRED, _('Role name'));
    }
    if(!isset($_POST['department']['id']))
    {
        $form_values['department']['error'] = _('Invalid department specified');
    }
    if(!isset($_POST['competencygroup']['id']))
    {
        $form_values['competencygroup']['error'] = _('Invalid competency group specified');
    }

    return $form_values;
}

function get_departments_assoc()
{
    $departments = R::$adapter->getAssoc('select id, name from department where tenant_id = ' . $_SESSION['current_user']->tenant_id);

    return $departments;
}

function get_competencygroups_assoc()
{
    $competencygroups = R::$adapter->getAssoc('select id, name from competencygroup where general != 1 AND tenant_id = ' . $_SESSION['current_user']->tenant_id);

    return $competencygroups;
}
