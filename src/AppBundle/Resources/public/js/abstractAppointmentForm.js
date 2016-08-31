var formAbstractAppointment = jQuery('form[name="appbundle_abstractappointment"]');
if(formAbstractAppointment.length > 0) {

    const
        repeat_type_once = 0,
        repeat_type_daily = 1,
        repeat_type_weekly = 2,
        repeat_type_monthly = 3,
        repeat_type_yearly = 4;

    function activateElement(element) {
        element
            .show().closest('.form-group').show();

    }

    function deactivateElement(element) {
        element
            .hide()
            .removeClass('repeatTypeDaily')
            .removeClass('repeatTypeWeekly')
            .removeClass('repeatTypeMonthly')
            .removeClass('repeatTypeYearly')
        .closest('.form-group').hide();
    }

    function activateDailyFields(formAbstractAppointment) {
    }
    function activateWeeklyFields(formAbstractAppointment) {
        activateElement(formAbstractAppointment.find('input[type="checkbox"][name="appbundle_abstractappointment[weekdayMonday]"]'));
        activateElement(formAbstractAppointment.find('input[type="checkbox"][name="appbundle_abstractappointment[weekdayTuesday]"]'));
        activateElement(formAbstractAppointment.find('input[type="checkbox"][name="appbundle_abstractappointment[weekdayWednesday]"]'));
        activateElement(formAbstractAppointment.find('input[type="checkbox"][name="appbundle_abstractappointment[weekdayThursday]"]'));
        activateElement(formAbstractAppointment.find('input[type="checkbox"][name="appbundle_abstractappointment[weekdayFriday]"]'));
        activateElement(formAbstractAppointment.find('input[type="checkbox"][name="appbundle_abstractappointment[weekdaySaturday]"]'));
        activateElement(formAbstractAppointment.find('input[type="checkbox"][name="appbundle_abstractappointment[weekdaySunday]"]'));
    }
    function activateMonthlyFields(formAbstractAppointment) {
        activateElement(formAbstractAppointment.find('input[name="appbundle_abstractappointment[repeatInterval]"]'));
        activateElement(formAbstractAppointment.find('select[name="appbundle_abstractappointment[repeatMonth][month]"]'));
        activateElement(formAbstractAppointment.find('input[type="checkbox"][name="appbundle_abstractappointment[monthlyOnWeekday]"]'));
    }
    function activateYearlyFields(formAbstractAppointment) {
        activateElement(formAbstractAppointment.find('input[name="appbundle_abstractappointment[repeatInterval]"]'));
    }
    function deactivateFields(formAbstractAppointment) {
        deactivateElement(formAbstractAppointment.find('input[name="appbundle_abstractappointment[repeatInterval]"]'));
        deactivateElement(formAbstractAppointment.find('select[name="appbundle_abstractappointment[repeatMonth][month]"]'));
        deactivateElement(formAbstractAppointment.find('input[type="checkbox"][name="appbundle_abstractappointment[monthlyOnWeekday]"]'));
        deactivateElement(formAbstractAppointment.find('input[type="checkbox"][name="appbundle_abstractappointment[weekdayMonday]"]'));
        deactivateElement(formAbstractAppointment.find('input[type="checkbox"][name="appbundle_abstractappointment[weekdayTuesday]"]'));
        deactivateElement(formAbstractAppointment.find('input[type="checkbox"][name="appbundle_abstractappointment[weekdayWednesday]"]'));
        deactivateElement(formAbstractAppointment.find('input[type="checkbox"][name="appbundle_abstractappointment[weekdayThursday]"]'));
        deactivateElement(formAbstractAppointment.find('input[type="checkbox"][name="appbundle_abstractappointment[weekdayFriday]"]'));
        deactivateElement(formAbstractAppointment.find('input[type="checkbox"][name="appbundle_abstractappointment[weekdaySaturday]"]'));
        deactivateElement(formAbstractAppointment.find('input[type="checkbox"][name="appbundle_abstractappointment[weekdaySunday]"]'));
    }

    function initAbstractAppointment(formAbstractAppointment) {
        var repeatType = formAbstractAppointment.find('select[name="appbundle_abstractappointment[repeatType][type]"]'),
            monthlyOnWeekday = formAbstractAppointment.find('input[type="checkbox"][name="appbundle_abstractappointment[monthlyOnWeekday]"]');

        repeatType.on('change', function(element) {
            console.log('Change detected:', element);
            var newRepeatType = jQuery(this).val();
            deactivateFields(formAbstractAppointment);
            if(newRepeatType == repeat_type_daily) {
                activateDailyFields(formAbstractAppointment);
            }
            else if(newRepeatType == repeat_type_weekly) {
                activateWeeklyFields(formAbstractAppointment);
            }
            else if(newRepeatType == repeat_type_monthly) {
                activateMonthlyFields(formAbstractAppointment);
            }
            else if(newRepeatType == repeat_type_yearly) {
                activateYearlyFields(formAbstractAppointment);
            }
            console.log('iiiehhh!! ' + jQuery(this).val());
        });
        repeatType.trigger('change');
    }

    initAbstractAppointment(formAbstractAppointment);
};