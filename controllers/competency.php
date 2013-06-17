<?php

function create_competency()
{
    security_authorize(MANAGER);

    $competencygroups = get_competencygroups_assoc();

    global $smarty;
    $smarty->assign('group_options', $competencygroups);

    return html($smarty->fetch('competency/competency.tpl'));
}

function create_competency_post()
{
    security_authorize(MANAGER);

    $keys_to_check = array('name' => $_POST['name'],
        'competencygroup' => array('load_bean' => true, 'id' => $_POST['competencygroup']['id'], 'type' => 'competencygroup'));

    $id = params('id');

    if(isset($id) && !empty($id))
    {
        $_POST['id'] = $id;
        $keys_to_check['id'] = array('load_bean' => true, 'id' => $_POST['id'], 'type' => 'competency');
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
            return edit_competency();
        }

        return create_competency();
    }

    $competency = R::graph($_POST);

    // Check if the department is a new department
    if($competency->id != 0)
    {
        $message = sprintf(UPDATE_SUCCESS, _('competency'), $competency->name);
    }
    else
    {
        $message = sprintf(CREATE_SUCCESS, _('competency'), $competency->name);
    }

    R::store($competency);

    header('Location: ' . MANAGER_URI . 'competencies?success=' . $message);
}

function view_competencies()
{
    security_authorize(MANAGER);

    $competencies = R::findAll('competency');
    $competencygroups = R::findAll('competencygroup');

    global $smarty;
    $smarty->assign('competencies', $competencies);
    $smarty->assign('competencygroups', $competencygroups);

    return html($smarty->fetch('competency/competencies.tpl'));
}

function edit_competency()
{
    security_authorize(MANAGER);

    $competency = R::load('competency', params('id'));

    if($competency->id == 0)
    {
        header('Location: ' . ADMIN_URI . 'competencies?error=' . sprintf(BEAN_NOT_FOUND, _('competency')));
        exit;
    }

    $competencygroups = get_competencygroups_assoc();

    global $smarty;
    $smarty->assign('group_options', $competencygroups);
    $smarty->assign('competency_name', $competency->name);
    $smarty->assign('update', true);

    return html($smarty->fetch('competency/competency.tpl'));
}

function delete_competency_confirmation()
{
    security_authorize(MANAGER);

    $competency = R::load('competency', params('id'));

    if($competency->id == 0)
    {
        $message = sprintf(BEAN_NOT_FOUND, 'competency');
        header('Location: ' . MANAGER_URI . 'competencies?error=' . $message);
    }

    global $smarty;
    $smarty->assign('type', 'competency');
    $smarty->assign('competency', $competency);
    $smarty->assign('level_uri', MANAGER_URI);

    return html($smarty->fetch('common/delete_confirmation.tpl'));
}

function delete_competency()
{
    security_authorize(MANAGER);

    $competency = R::load('competency', params('id'));

    if($competency->id == 0)
    {
        $message = sprintf(BEAN_NOT_FOUND, 'competency');
        header('Location: ' . MANAGER_URI . 'competencies?error=' . $message);
        exit;
    }

    R::trash($competency);

    $message = sprintf(DELETE_SUCCESS, 'competency', $competency->name);
    header('Location: ' . MANAGER_URI . 'competencies?success=' . $message);
}