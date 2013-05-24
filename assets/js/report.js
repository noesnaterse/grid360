google.load('visualization', '1', {packages: ['corechart']});
function draw_chart()
{
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Competency name');
    data.addColumn('number', 'Average');
    data.addColumn('number', 'Own');

    for(var i in averages)
    {
        average = averages[i];
        if(average.enabled == true)
        {
            switch(state)
            {
                case 0:
                    break;
                case 1:
                    if(!isNaN(average['average']))
                    {
                        data.addRow([average['name'], average['average'], null]);
                    }
                    break;
                case 2:
                    if(!isNaN(average['own_rating']))
                    {
                        data.addRow([average['name'], 0, average['own_rating']]);
                    }
                    break;
                case 4:
                    if(!isNaN(average['own_rating']) && !isNaN(average['average']))
                    {
                        data.addRow([average['name'], average['average'], average['own_rating']]);
                    }
                    break;
                default:
                    data.addRow([average['name'], average['average'], average['own_rating']]);
                    break;
            }
        }
    }
    chart.draw(data, options);
};

$(document).ready(function()
{
    state = 3;
    chart = new google.visualization.BarChart(document.getElementById('chart-div'));

    options =
    {
        title: 'Average ratings',
        fontSize: 12,
        animation:
        {
            duration: 500
        },
        vAxis: {title: 'Competencies'},
        hAxis:
        {
            viewWindowMode: 'explicit',
            viewWindow: {
                max: 5.1, // Set to 5.1 so the number 5 shows on the chart as well
                min: 0
            },
            title: 'Points'
        }
    };

    $('.comment-box').bind('mousewheel DOMMouseScroll', function(e)
    {
        var delta = e.wheelDelta || -e.detail;
        this.scrollTop += ( delta < 0 ? 1 : -1 ) * 30;
    });

    $('.tabbable a').click(function(e)
    {
        e.preventDefault();
        $(this).tab('show');
    });

    $('#select-all').click(function()
    {
        $('#competency-boxes input:checkbox:not(:checked)').click();
        $('#competency-boxes input:checkbox:not(:checked)').attr('checked', true);
        check_checked();
    });

    $('#deselect-all').click(function()
    {
        $('#competency-boxes input:checked').attr('checked', false);
        check_checked();
    });

    $('input[name="check_own"]').click(function()
    {
        $('input[name="check_comparison"]').attr('checked', false);
    });

    $('input[name="check_average"]').click(function()
    {
        $('input[name="check_comparison"]').attr('checked', false);
    });

    $('input[name="check_comparison"]').click(function()
    {
        if($('input[name="check_comparison"]').is(':not(:checked)'))
        {
            $('input[name="check_average"]').click();
            $('input[name="check_own"]').attr('checked', 'checked');
        }
    });

    $('#options-label').click(function()
    {
        $('#options').slideToggle();
        if($('#options-label i').attr('class') == 'icon-minus-sign')
        {
            $('#options-label i').attr('class', 'icon-plus-sign');
            $('#options-label small').text('(show)');
        }
        else
        {
            $('#options-label i').attr('class', 'icon-minus-sign');
            $('#options-label small').text('(hide)');
        }
    });

    function check_checked()
    {
        state = 0;
        $('input[type="checkbox"]').each(function()
        {
            if(($(this).attr('name') == 'check_average' || $(this).attr('name') == 'check_own') || $(this).attr('name') == 'check_comparison')
            {
                if($(this).attr('name') == 'check_comparison')
                {
                    if($(this).is(':checked'))
                    {
                        state = 4;
                        $('input[name="check_average"]').attr('checked', false);
                        $('input[name="check_own"]').attr('checked', false);
                        return;
                    }
                }
                if($(this).is(':checked'))
                {
                    state += parseInt($(this).val());
                }
            }
            else if($(this).is(':checked'))
            {
                averages[$(this).val()].enabled = 1;
            }
            else
            {
                averages[$(this).val()].enabled = 0;
            }
        });

        draw_chart();
    }

    $('input[type=checkbox]').not('input[name="check_comparison"]').attr('checked', true);
    $('input[type=checkbox]').on('click', check_checked);
    draw_chart();
});