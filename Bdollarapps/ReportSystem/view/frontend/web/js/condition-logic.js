require(['jquery','domReady!'], function($){ 
    
    

    var classes_enable = '.annual_income_before_taxes, .children_under_age_of_18, .retirement_accounts_still_held_with_previous_employer, .what_type_of_life_insurance_do_you_have, .what_other_investments_do_you_have, .what_is_the_approximate_balance_of_your_savings_and_or_checking_accounts, .are_your_beneficiaries_up_to_date_on_all_your_accounts, .what_household_debts_do_you_have, .do_you_currently_own_or_rent_your_home, .is_one_of_your_goals_to_purchase_a_home_in_the_next_12_months, .do_you_plan_on_purchasing_a_second_home_or_condo_in_the_next_year, .what_is_your_average_annual_household_tax_refund, .is_your_household_in_the_process_of_receiving_an_inheritance, .have_you_compared_prices_on_auto_and_or_home_insurance_in_the_past_year, .do_you_currently_have_a_smart_home_security_system, .do_you_have_health_insurance, .do_you_have_identity_theft_protection, .do_you_have_a_will, .are_you_open_to_additional_strategies_to_increase_your_income';
    $(classes_enable).addClass('enable');

    var classes_step_disable = '.do_you_turn_50_this_calendar_year, .do_you_turn_72_at_any_time_during_this_calendar_year, .dependent_or_parent_living_with_you, .do_you_personally_pay_more_than_51_of_the_entire_household_expenses, .retiring_in_12_months, .how_is_your_company_structured, .is_your_modified_gross_income_less_than_129000, .is_your_modified_gross_income_less_than_204000, .college_savings_plan_for_children, .how_many_employees_do_you_have, .independent_contractors_work_for_you, .your_business_partners, .business_partners_life_insurance, .retirement_plans_for_you_or_your_employees, .are_the_total_assets_in_your_401K_platform_for_all_employees_in_excess_of_1000000, .your_employee_participation_rate_less_than_50, .approximate_balance_retirement_account_held_with_previous_employer, .work_sponsored_retirement_plan, .retirement_plan_do_you_have_with_your_current_employer, .balance_of_your_work_sponsored_retirement_plan, .are_you_contributing_to_your_401K_plan, .does_your_company_provide_a_match, .how_much_are_you_contributing, .how_much_coverage_do_you_have_with_your_Term_Insurance, .how_much_coverage_do_you_have_with_your_group_insurance, .how_much_coverage_do_you_have_with_your_insurance_with_the_savings_account, .what_is_the_approximate_balance_on_your_roth_ira, .how_much_do_you_contribute_annually_to_your_roth_ira, .what_is_the_approximate_balance_on_your_traditional_ira, .how_much_do_you_contribute_annually_to_your_traditional_ira, .what_is_the_approximate_balance_on_your_annuity, .what_is_the_approximate_balance_on_your_cd, .is_the_interest_rate_on_your_CD_less_than_2, .what_is_the_approximate_balance_on_your_mutual_fund, .do_you_plan_on_financing_part_of_this_home_purchase, .will_you_be_paying_cash_for_that_entire_transaction_or_will_some_of_it_be_financed_as_well, .does_your_household_expect_to_receive_an_inheritance_in_the_next_2_years, .what_is_the_approximate_expected_amount_of_the_inheritance, .would_you_like_to_explore_some_options_for_a_smart_home_security_system, .has_your_will_been_updated_in_the_past_3_years';
    $(classes_step_disable).addClass('step_disable');

    $('.webforms form fieldset').hide();
    $('.webforms form fieldset.active').show();

    var add_button_form = '<div class="next-prev-wrap"><span class="prev-btn">Prev</span><span class="next-btn">Next</span></div>';
    $('.webforms .actions-toolbar').before(add_button_form );
    $('.webforms .actions-toolbar').hide();

    
    var check_by_value = $('.how_do_you_file_your_taxes').find('input').attr('name');
    var check_by_value = $( '.how_do_you_file_your_taxes input[name="'+check_by_value+'"]' );
    check_by_value.on("change", function(e) {
        e.stopImmediatePropagation();
        switch($(this).val()) {
            case 'Single':
                $('.spouse_do_you_turn_50_this_calendar_year').addClass('step_disable');
                $('.spouse_do_you_turn_72_at_any_time_during_this_calendar_year').addClass('step_disable');
                $('.spouse_what_is_your_age').addClass('disable');
                $('.dependent_or_parent_living_with_you').addClass('enable');
                $('.dependent_or_parent_living_with_you').removeClass('step_disable');
                $('.spouse_what_is_your_age').addClass('disable');
                $('.do_you_personally_pay_more_than_51_of_the_entire_household_expenses').addClass('enable');
                $('.self_employee').addClass('enable');
                $('.retiring_in_12_months').addClass('enable');
                $('.spouse_employee_status').addClass('disable');
                $('.spouse_retiring_in_12_months').addClass('disable');
                $('.spouse_annual_income_before_taxes').addClass('disable');
                $('.spouse_what_is_the_approximate_balance_on_your_roth_ira').addClass('disable');
                $('.spouse_how_much_do_you_contribute_annually_to_your_roth_ira').addClass('disable');
                $('.spouse_how_much_do_you_contribute_annually_to_your_roth_ira').removeClass('enable');
                $('.spouse_how_much_do_you_contribute_annually_to_your_roth_ira').addClass('step_disable');
                $('.self_how_much_are_you_contributing').addClass('disable');
                $('.self_how_much_are_you_contributing').removeClass('enable');
                $('.self_how_much_are_you_contributing').addClass('step_disable');
                $('.spouse_retirement_plan_do_you_have_with_your_current_employer').addClass('disable');
                $('.spouse_retirement_plan_do_you_have_with_your_current_employer').removeClass('enable');
                $('.spouse_retirement_plan_do_you_have_with_your_current_employer').addClass('step_disable');
                $('.spouse_work_sponsored_retirement_plan').addClass('disable');
                $('.spouse_annual_income_before_taxes').addClass('disable');
                $('.spouse_what_is_the_approximate_balance_on_your_mutual_fund').addClass('disable');
                $('.spouse_what_is_the_approximate_balance_on_your_mutual_fund').removeClass('enable');
                $('.spouse_what_is_the_approximate_balance_on_your_mutual_fund').addClass('step_disable');
                $('.self_how_is_your_company_structured').addClass('disable');
                $('.spouse_what_type_of_life_insurance_do_you_have').addClass('disable');
                $('.spouse_what_type_of_life_insurance_do_you_have').removeClass('enable');
                $('.spouse_what_type_of_life_insurance_do_you_have').addClass('step_disable');
                $('.spouse_approximate_balance_retirement_account_held_with_previous_employer').addClass('disable');
                $('.spouse_retirement_accounts_still_held_with_previous_employer').addClass('disable');
                $('.spouse_do_you_have_health_insurance').addClass('disable');
                $('.spouse_do_you_have_health_insurance').removeClass('enable');
                $('.spouse_do_you_have_health_insurance').addClass('step_disable');
                $('.self_what_is_the_approximate_balance_on_your_traditional_ira').addClass('disable');
                $('.self_what_is_the_approximate_balance_on_your_traditional_ira').removeClass('enable');
                $('.self_what_is_the_approximate_balance_on_your_traditional_ira').addClass('step_disable');
                $('.self_how_much_do_you_contribute_annually_to_your_traditional_ira').addClass('disable');
                $('.spouse_do_you_have_identity_theft_protection').addClass('disable');
                $('.spouse_do_you_have_identity_theft_protection').removeClass('enable');
                $('.spouse_do_you_have_identity_theft_protection').addClass('step_disable');

                $('.spouse_do_you_have_a_will').addClass('disable');
                $('.spouse_do_you_have_a_will').removeClass('enable');
                $('.spouse_do_you_have_a_will').addClass('step_disable');
                
                $('.spouse_retiring_in_12_months').addClass('disable');

                $('.spouse_how_is_your_company_structured').addClass('disable');

                $('.spouse_independent_contractors_work_for_you').addClass('disable');

                $('.spouse_has_your_will_been_updated_in_the_past_3_years').addClass('disable');
                $('.spouse_has_your_will_been_updated_in_the_past_3_years').removeClass('enable');
                $('.spouse_has_your_will_been_updated_in_the_past_3_years').addClass('step_disable');

                $('.spouse_your_business_partners').addClass('disable'); 
                $('.spouse_how_many_employees_do_you_have').addClass('disable'); 
                $('.spouse_retirement_plans_for_you_or_your_employees').addClass('disable'); 
                $('.spouse_business_partners_life_insurance').addClass('disable'); 
                $('.spouse_what_is_the_approximate_balance_on_your_annuity').addClass('disable');

                $('.spouse_your_employee_participation_rate_less_than_50').addClass('disable');
                $('.spouse_are_the_total_assets_in_your_401K_platform_for_all_employees_in_excess_of_1000000').addClass('disable');

                $('.spouse_balance_of_your_work_sponsored_retirement_plan').addClass('disable');
                $('.spouse_balance_of_your_work_sponsored_retirement_plan').removeClass('enable');
                $('.spouse_balance_of_your_work_sponsored_retirement_plan').addClass('step_disable');

                $('.spouse_are_you_contributing_to_your_401K_plan').addClass('disable');

                $('.spouse_does_your_company_provide_a_match').addClass('disable');
                $('.spouse_does_your_company_provide_a_match').removeClass('enable');
                $('.spouse_does_your_company_provide_a_match').addClass('step_disable');

                $('.spouse_what_is_the_approximate_balance_on_your_cd').addClass('disable');
                $('.spouse_what_is_the_approximate_balance_on_your_cd').removeClass('enable');
                $('.spouse_what_is_the_approximate_balance_on_your_cd').addClass('step_disable');

                $('.spouse_is_the_interest_rate_on_your_CD_less_than_2').addClass('disable');
                $('.spouse_is_the_interest_rate_on_your_CD_less_than_2').removeClass('enable');
                $('.spouse_is_the_interest_rate_on_your_CD_less_than_2').addClass('step_disable');
            break;

            case 'Head of Household':
                $('.spouse_do_you_turn_50_this_calendar_year').addClass('step_disable');
                $('.spouse_do_you_turn_72_at_any_time_during_this_calendar_year').addClass('step_disable');
                $('.spouse_what_is_your_age').addClass('disable');
                $('.dependent_or_parent_living_with_you').addClass('enable');
                $('.dependent_or_parent_living_with_you').removeClass('step_disable');
                $('.spouse_what_is_your_age').addClass('disable');
                $('.spouse_employee_status').addClass('disable');
                $('.spouse_retiring_in_12_months').addClass('disable');
                $('.spouse_annual_income_before_taxes').addClass('disable');
                
                $('.spouse_retirement_plan_do_you_have_with_your_current_employer').addClass('disable');

                $('.is_your_modified_gross_income_less_than_204000').removeClass('enable');
                $('.is_your_modified_gross_income_less_than_204000').addClass('step_disable');

                $('.spouse_what_is_the_approximate_balance_on_your_roth_ira').addClass('enable');
                $('.spouse_has_your_will_been_updated_in_the_past_3_years').addClass('enable');
                $('.spouse_what_is_the_approximate_balance_on_your_mutual_fund').addClass('enable');
                $('.spouse_what_is_the_approximate_balance_on_your_cd').addClass('enable');
                $('.spouse_is_the_interest_rate_on_your_CD_less_than_2').addClass('enable');
                $('.self_what_is_the_approximate_balance_on_your_traditional_ira').addClass('enable');
                $('.self_how_much_do_you_contribute_annually_to_your_traditional_ira').addClass('disable');
                $('.spouse_how_much_do_you_contribute_annually_to_your_roth_ira').addClass('disable');
                $('.spouse_what_is_the_approximate_balance_on_your_annuity').addClass('disable');
                $('.spouse_approximate_balance_retirement_account_held_with_previous_employer').addClass('disable');
                $('.self_how_much_are_you_contributing').addClass('disable');
                $('.spouse_are_you_contributing_to_your_401K_plan').addClass('disable');
                $('.spouse_balance_of_your_work_sponsored_retirement_plan').addClass('disable');
                $('.spouse_employment_status').addClass('disable');
                $('.spouse_work_sponsored_retirement_plan').addClass('disable');
                $('.self_how_is_your_company_structured').addClass('disable');
                $('.spouse_annual_income_before_taxes').addClass('disable');
                $('.spouse_retiring_in_12_months').addClass('disable');
                $('.spouse_how_is_your_company_structured').addClass('disable');
                $('.spouse_what_type_of_life_insurance_do_you_have').addClass('disable');

                $('.spouse_retirement_accounts_still_held_with_previous_employer').addClass('disable');
                $('.spouse_do_you_have_health_insurance').addClass('disable');

                $('.spouse_do_you_have_identity_theft_protection').addClass('disable');
                $('.spouse_do_you_have_a_will').addClass('disable');

                $('.dependent_or_parent_living_with_you').addClass('disable');
                $('.dependent_or_parent_living_with_you').removeClass('enable');
                $('.dependent_or_parent_living_with_you').addClass('tab_disabled');

                $('.do_you_personally_pay_more_than_51_of_the_entire_household_expenses').removeClass('enable');
                $('.do_you_personally_pay_more_than_51_of_the_entire_household_expenses').addClass('tab_disabled');

                $('.spouse_independent_contractors_work_for_you').addClass('disable');
                $('.spouse_your_business_partners').addClass('disable');
                $('.spouse_retirement_plans_for_you_or_your_employees').addClass('disable');
                $('.spouse_your_employee_participation_rate_less_than_50').addClass('disable');
                $('.spouse_are_the_total_assets_in_your_401K_platform_for_all_employees_in_excess_of_1000000').addClass('disable');
                $('.spouse_business_partners_life_insurance').addClass('disable');
            break;

            case 'Married Filing Jointly':
                $('.spouse_do_you_turn_50_this_calendar_year').removeClass('step_disable');
                $('.spouse_do_you_turn_72_at_any_time_during_this_calendar_year').removeClass('step_disable');
                $('.do_you_turn_50_this_calendar_year').removeClass('step_disable');
                $('.spouse_do_you_turn_50_this_calendar_year').removeClass('step_disable');
                $('.spouse_what_is_your_age').removeClass('disable');
                $('.dependent_or_parent_living_with_you').removeClass('enable');
                $('.dependent_or_parent_living_with_you').addClass('step_disable');
                $('.do_you_personally_pay_more_than_51_of_the_entire_household_expenses').removeClass('enable');
                $('.do_you_personally_pay_more_than_51_of_the_entire_household_expenses').addClass('step_disable');
                $('.spouse_what_is_your_age').removeClass('disable');
                $('.spouse_employee_status').removeClass('disable');
                $('.spouse_retiring_in_12_months').removeClass('disable');
            break;

            case 'Married Filing Seperately':
                $('.spouse_do_you_turn_50_this_calendar_year').removeClass('step_disable');
                $('.spouse_do_you_turn_72_at_any_time_during_this_calendar_year').removeClass('step_disable');
                $('.do_you_turn_50_this_calendar_year').removeClass('step_disable');
                $('.spouse_do_you_turn_50_this_calendar_year').removeClass('step_disable');
                $('.spouse_what_is_your_age').removeClass('disable');
                $('.dependent_or_parent_living_with_you').removeClass('enable');
                $('.dependent_or_parent_living_with_you').addClass('step_disable');
                $('.do_you_personally_pay_more_than_51_of_the_entire_household_expenses').removeClass('enable');
                $('.do_you_personally_pay_more_than_51_of_the_entire_household_expenses').addClass('step_disable');
                $('.spouse_what_is_your_age').removeClass('disable');
                $('.spouse_employee_status').removeClass('disable');
                $('.spouse_retiring_in_12_months').removeClass('disable');
            break;
        }
    });

    var check_by_value = $('.what_is_your_age .self_what_is_your_age').find('input').attr('name');
    var check_by_value = $( '.what_is_your_age input[name="'+check_by_value+'"]' );
    check_by_value.on("change", function(e) {
        e.stopImmediatePropagation();
        if ($(this).val() == 49) {
            $('.do_you_turn_50_this_calendar_year').removeClass('step_disable');
            $('.do_you_turn_50_this_calendar_year').addClass('enable');
            $('.self_do_you_turn_72_at_any_time_during_this_calendar_year').addClass('step_disable');
            $('.dependent_or_parent_living_with_you').addClass('enable');
            $('.dependent_or_parent_living_with_you').removeClass('step_disable');
        } 
        else if($(this).val() == 72) {
            $('.do_you_turn_50_this_calendar_year').addClass('step_disable');
            $('.self_do_you_turn_72_at_any_time_during_this_calendar_year').removeClass('step_disable');
            $('.self_do_you_turn_72_at_any_time_during_this_calendar_year').addClass('enable');
            $('.do_you_turn_50_this_calendar_year').removeClass('enable');
            $('.dependent_or_parent_living_with_you').addClass('enable');
            $('.dependent_or_parent_living_with_you').removeClass('step_disable');
        }
        else{
            $('.do_you_turn_50_this_calendar_year').addClass('step_disable');
            $('.do_you_turn_50_this_calendar_year').removeClass('enable');
            $('.self_do_you_turn_72_at_any_time_during_this_calendar_year').addClass('step_disable');
            $('.self_do_you_turn_72_at_any_time_during_this_calendar_year').removeClass('enable');
            $('.dependent_or_parent_living_with_you').addClass('enable');
            $('.dependent_or_parent_living_with_you').removeClass('step_disable');
        }
    });

    var check_by_value = $('.dependent_or_parent_living_with_you').find('input').attr('name');
    var check_by_value = $( '.dependent_or_parent_living_with_you input[name="'+check_by_value+'"]' );
    check_by_value.on("change", function(e) {   
        e.stopImmediatePropagation();
        switch($(this).val()) {
            case 'Yes':
                /*$('.do_you_personally_pay_more_than_51_of_the_entire_household_expenses').show();*/
                $('.do_you_personally_pay_more_than_51_of_the_entire_household_expenses').removeClass('step_disable');
                $('.do_you_personally_pay_more_than_51_of_the_entire_household_expenses').addClass('enable');
                break;
            case 'No':
                /*$('.do_you_personally_pay_more_than_51_of_the_entire_household_expenses').hide();*/
                $('.do_you_personally_pay_more_than_51_of_the_entire_household_expenses').removeClass('enable');
                $('.do_you_personally_pay_more_than_51_of_the_entire_household_expenses').addClass('step_disable');
                break;
            }
    });

    var check_by_value = $( '.self_employee input[value="Employee"]' );
    check_by_value.on("change", function(e) {   
        e.stopImmediatePropagation();
        if($(this).is(':checked')){
            $('.self_work_sponsored_retirement_plan').show();
            $('.self_retiring_in_12_months').removeClass('disable');
            /*$('.retiring_in_12_months').show();*/
            $('.retiring_in_12_months').removeClass('step_disable');
            $('.retiring_in_12_months').addClass('enable');
            $('.work_sponsored_retirement_plan').removeClass('step_disable');
            $('.work_sponsored_retirement_plan').addClass('enable');

            $('.self_employee input[value="Retired"]').prop('checked', false);
            $('.self_employee input[value="Home Maker / Unemployed"]').prop('checked', false);
            $('.retiring_in_12_months ').removeClass('step_disable');
            $('.retiring_in_12_months').addClass('enable');
            $('.how_is_your_company_structured').addClass('step_disable');
            $('.how_is_your_company_structured').removeClass('enable');

    
        } else {
            
        }
   });

   var check_by_value = $( '.self_employee input[value="Self Employed/ Business owner"]' );
    check_by_value.on("change", function(e) {   
        e.stopImmediatePropagation();
        if($(this).is(':checked')){
            $('.self_employee input[value="Retired"]').prop('checked', false);
            $('.self_employee input[value="Home Maker / Unemployed"]').prop('checked', false);
            $('.retiring_in_12_months ').removeClass('step_disable');
            $('.retiring_in_12_months').addClass('enable');
            $('.independent_contractors_work_for_you').removeClass('step_disable');
            $('.independent_contractors_work_for_you').addClass('enable');
            $('.how_is_your_company_structured').removeClass('step_disable');
            $('.how_is_your_company_structured').addClass('enable');
        } else {
            $('.how_is_your_company_structured').addClass('step_disable');
            $('.how_is_your_company_structured').removeClass('enable');
            $('.independent_contractors_work_for_you').addClass('step_disable');
            $('.independent_contractors_work_for_you').removeClass('enable');
        }
   });

    var check_by_value = $( '.self_employee input[value="Retired"]' );
    check_by_value.on("change", function(e) {   
        e.stopImmediatePropagation();
        if($(this).is(':checked')){
            $('.self_employee input[value="Employee"]').prop('checked', false);
            $('.self_employee input[value="Self Employed/ Business owner"]').prop('checked', false);
            $('.retiring_in_12_months ').addClass('step_disable');
            $('.retiring_in_12_months').removeClass('enable');
            $('.annual_income_before_taxes ').removeClass('step_disable');
            $('.annual_income_before_taxes').addClass('enable');
            $('.how_is_your_company_structured').addClass('step_disable');
            $('.how_is_your_company_structured').removeClass('enable');

    
        } else {
            console.log('hy men');
        }
   });

    var check_by_value = $( '.self_employee input[value="Home Maker / Unemployed"]' );
    check_by_value.on("change", function(e) {   
        e.stopImmediatePropagation();
        if($(this).is(':checked')){
            $('.self_employee input[value="Employee"]').prop('checked', false);
            $('.self_employee input[value="Self Employed/ Business owner"]').prop('checked', false);
            $('.self_work_sponsored_retirement_plan').removeClass('enable');
            $('.self_work_sponsored_retirement_plan').addClass('step_disable');
            $('.retiring_in_12_months ').addClass('step_disable');
            $('.retiring_in_12_months').removeClass('enable');
            $('.annual_income_before_taxes ').removeClass('step_disable');
            $('.annual_income_before_taxes').addClass('enable');
            $('.how_is_your_company_structured').addClass('step_disable');
            $('.how_is_your_company_structured').removeClass('enable');
            
    
        } else {
            console.log('hy men');
        }
   });

   var check_by_value = $('.self_annual_income_before_taxes').find('input').attr('name');
   var check_by_value = $( '.self_annual_income_before_taxes input[name="'+check_by_value+'"]' );
   check_by_value.on("change", function(e) {   
        e.stopImmediatePropagation();
        if ($(this).val() >= 129001 &&
            $("input[name='how_do_you_file_your_taxes']:checked").val() != 'Married Filing Jointly') {
            $('.is_your_modified_gross_income_less_than_129000').removeClass('step_disable');
            $('.is_your_modified_gross_income_less_than_129000').addClass('enable');
            $('.self_is_your_modified_gross_income_less_than_129000').removeClass('disable');
        } else {
            $('.self_is_your_modified_gross_income_less_than_129000').addClass('disable');
            $("input[name='self_is_your_modified_gross_income_less_than_129000']").prop("checked", false);
            $('.is_your_modified_gross_income_less_than_129000').removeClass('enable');
            $('.is_your_modified_gross_income_less_than_129000').addClass('step_disable');
        }
    });

   var check_by_value = $('.children_under_age_of_18').find('input').attr('name');
   var check_by_value = $( '.children_under_age_of_18 input[name="'+check_by_value+'"]' );
   check_by_value.on("change", function(e) {   
        e.stopImmediatePropagation();
       switch($(this).val()) {
           case 'Yes':
               /*$('.college_savings_plan_for_children').show();*/
               $('.college_savings_plan_for_children').removeClass('step_disable');
               $('.college_savings_plan_for_children').addClass('enable');
               break;
           case 'No':
              /* $('.college_savings_plan_for_children').hide();*/
               $('.college_savings_plan_for_children').removeClass('enable');
               $('.college_savings_plan_for_children').addClass('step_disable');
               break;
           }
   });

var check_by_value = $( '.college_savings_plan_for_children input[value="None"]' );
check_by_value.on("change", function(e) {   
    if($('.college_savings_plan_for_children input[value="None"]').is(':checked') == true) {
        $('.college_savings_plan_for_children input[value="529"]').prop("checked", false);
        $('.college_savings_plan_for_children input[value="Coverdell"]').prop("checked", false);
        $('.college_savings_plan_for_children input[value="Minor Roth"]').prop("checked", false);
        $('.college_savings_plan_for_children input[value="Savings Account"]').prop("checked", false);
        $('.college_savings_plan_for_children input[value="UGMA/UTMA"]').prop("checked", false);
        $('.college_savings_plan_for_children input[value="Bonds"]').prop("checked", false);
        $('.college_savings_plan_for_children input[value="My children’s college is already fully funded"]').prop("checked", false);
        $('.college_savings_plan_for_children input[value="Not interested in funding college"]').prop("checked", false);

    }
});


var check_by_value = $( '.college_savings_plan_for_children input[value="529"]' );
check_by_value.on("change", function(e) {  
    if($('.college_savings_plan_for_children input[value="529"]').is(':checked') == true) {
        $('.college_savings_plan_for_children input[value="None"]').prop("checked", false);
        $('.college_savings_plan_for_children input[value="My children’s college is already fully funded"]').prop("checked", false);
        $('.college_savings_plan_for_children input[value="Not interested in funding college"]').prop("checked", false);
    }
});

var check_by_value = $( '.college_savings_plan_for_children input[value="Coverdell"]' );
check_by_value.on("change", function(e) {  
    if($('.college_savings_plan_for_children input[value="Coverdell"]').is(':checked') == true) {
        $('.college_savings_plan_for_children input[value="None"]').prop("checked", false);
        $('.college_savings_plan_for_children input[value="My children’s college is already fully funded "]').prop("checked", false);
        $('.college_savings_plan_for_children input[value="Not interested in funding college"]').prop("checked", false);
    }
});

var check_by_value = $( '.college_savings_plan_for_children input[value="Minor Roth"]' );
check_by_value.on("change", function(e) {  
    if($('.college_savings_plan_for_children input[value="Minor Roth"]').is(':checked') == true) {
        $('.college_savings_plan_for_children input[value="None"]').prop("checked", false);
        $('.college_savings_plan_for_children input[value="My children’s college is already fully funded"]').prop("checked", false);
        $('.college_savings_plan_for_children input[value="Not interested in funding college"]').prop("checked", false);
    }
});

var check_by_value = $( '.college_savings_plan_for_children input[value="Savings Account"]' );
check_by_value.on("change", function(e) {  
    if($('.college_savings_plan_for_children input[value="Savings Account"]').is(':checked') == true) {
        $('.college_savings_plan_for_children input[value="None"]').prop("checked", false);
        $('.college_savings_plan_for_children input[value="My children’s college is already fully funded"]').prop("checked", false);
        $('.college_savings_plan_for_children input[value="Not interested in funding college"]').prop("checked", false);
    }
});

var check_by_value = $( '.college_savings_plan_for_children input[value="UGMA/UTMA"]' );
check_by_value.on("change", function(e) {  
    if($('.college_savings_plan_for_children input[value="UGMA/UTMA"]').is(':checked') == true) {
        $('.college_savings_plan_for_children input[value="None"]').prop("checked", false);
        $('.college_savings_plan_for_children input[value="My children’s college is already fully funded"]').prop("checked", false);
        $('.college_savings_plan_for_children input[value="Not interested in funding college"]').prop("checked", false);
    }
});

var check_by_value = $( '.college_savings_plan_for_children input[value="Bonds"]' );
check_by_value.on("change", function(e) {  
    if($('.college_savings_plan_for_children input[value="Bonds"]').is(':checked') == true) {
        $('.college_savings_plan_for_children input[value="None"]').prop("checked", false);
        $('.college_savings_plan_for_children input[value="My children’s college is already fully funded"]').prop("checked", false);
        $('.college_savings_plan_for_children input[value="Not interested in funding college"]').prop("checked", false);
    }
});

var check_by_value = $( '.college_savings_plan_for_children input[value="My children’s college is already fully funded"]' );
check_by_value.on("change", function(e) {  
    if($('.college_savings_plan_for_children input[value="My children’s college is already fully funded"]').is(':checked') == true) {
        $('.college_savings_plan_for_children input[value="None"]').prop("checked", false);
        $('.college_savings_plan_for_children input[value="529"]').prop("checked", false);
        $('.college_savings_plan_for_children input[value="Coverdell"]').prop("checked", false);
        $('.college_savings_plan_for_children input[value="Minor Roth"]').prop("checked", false);
        $('.college_savings_plan_for_children input[value="Savings Account"]').prop("checked", false);
        $('.college_savings_plan_for_children input[value="UGMA/UTMA"]').prop("checked", false);
        $('.college_savings_plan_for_children input[value="Bonds"]').prop("checked", false);
        $('.college_savings_plan_for_children input[value="Not interested in funding college"]').prop("checked", false);
    }
});

var check_by_value = $( '.college_savings_plan_for_children input[value="Not interested in funding college"]' );
check_by_value.on("change", function(e) {  
    if($('.college_savings_plan_for_children input[value="Not interested in funding college"]').is(':checked') == true) {
        $('.college_savings_plan_for_children input[value="None"]').prop("checked", false);
        $('.college_savings_plan_for_children input[value="529"]').prop("checked", false);
        $('.college_savings_plan_for_children input[value="Coverdell"]').prop("checked", false);
        $('.college_savings_plan_for_children input[value="Minor Roth"]').prop("checked", false);
        $('.college_savings_plan_for_children input[value="Savings Account"]').prop("checked", false);
        $('.college_savings_plan_for_children input[value="UGMA/UTMA"]').prop("checked", false);
        $('.college_savings_plan_for_children input[value="Bonds"]').prop("checked", false);
        $('.college_savings_plan_for_children input[value="My children’s college is already fully funded"]').prop("checked", false);
    }
});

var check_by_value = $('.self_retirement_accounts_still_held_with_previous_employer').find('input').attr('name');
var check_by_value = $( '.self_retirement_accounts_still_held_with_previous_employer input[name="'+check_by_value+'"]' );
check_by_value.on("change", function(e) {  
    e.stopImmediatePropagation();
   switch($(this).val()) {
       case 'Yes':
           $('.approximate_balance_retirement_account_held_with_previous_employer').removeClass('step_disable');
           $('.approximate_balance_retirement_account_held_with_previous_employer').addClass('enable');
           break;
       case 'No':
           if ($(".self_retirement_accounts_still_held_with_previous_employer input[value='Yes']:checked").val() != 'yes') {
               $('.approximate_balance_retirement_account_held_with_previous_employer').removeClass('enable');
               $('.approximate_balance_retirement_account_held_with_previous_employer').addClass('step_disable');
           }
           break;
       }
});

var check_by_value = $('.self_work_sponsored_retirement_plan').find('input').attr('name');
var check_by_value = $( '.self_work_sponsored_retirement_plan input[name="'+check_by_value+'"]' );
check_by_value.on("change", function(e) {  
e.stopImmediatePropagation();
    switch($(this).val()) {
        case 'Yes':
            $('.retirement_plan_do_you_have_with_your_current_employer').removeClass('step_disable');
            $('.retirement_plan_do_you_have_with_your_current_employer').addClass('enable');
            break;
        case 'No':
            $('.retirement_plan_do_you_have_with_your_current_employer').removeClass('enable');
            $('.retirement_plan_do_you_have_with_your_current_employer').addClass('step_disable');

                var check_by_value_sub = $('.spouse_work_sponsored_retirement_plan').find('input').attr('name');
                var check_by_value_sub = $( '.spouse_work_sponsored_retirement_plan input[name="'+check_by_value+'"]:checked' );
            if ( check_by_value_sub.val() != 'yes') {
                $('.retirement_plan_do_you_have_with_your_current_employer').removeClass('enable');
                $('.retirement_plan_do_you_have_with_your_current_employer').addClass('step_disable');
            }
       break;
   }
});


var check_by_value = $( '.self_retirement_plan_do_you_have_with_your_current_employer input[value="401(k)"]' );
check_by_value.on("change", function(e) {  
    e.stopImmediatePropagation();
    if ( $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="401(k)"]').is(':checked') == true) {
       $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="None"]').prop("checked", false);
       $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="I have no idea"]').prop("checked", false);
       $('.balance_of_your_work_sponsored_retirement_plan').removeClass('step_disable');
       $('.balance_of_your_work_sponsored_retirement_plan').addClass('enable');

       $('.does_your_company_provide_a_match').removeClass('step_disable');
       $('.does_your_company_provide_a_match').addClass('enable');

       $('.are_you_contributing_to_your_401K_plan').removeClass('step_disable');
       $('.are_you_contributing_to_your_401K_plan').addClass('enable');
    } else {
        if ($('.self_retirement_plan_do_you_have_with_your_current_employer input[value="403(b)"]').is(':checked') == false &&
           $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="457"]').is(':checked') == false &&
           $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="SIMPLE"]').is(':checked') == false &&
           $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Profit-Sharing"]').is(':checked') == false &&
           $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Pension"]').is(':checked') == false &&
           $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Thrift Savings Plan (TSP)"]').is(':checked') == false &&
           $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="None"]').is(':checked') == false &&
           $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Other"]').is(':checked') == false &&
           $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="I have no idea"]').is(':checked') == false
       ) {
            $('.self_balance_of_your_work_sponsored_retirement_plan').addClass('disable');
        }

       if ( $('.self_retirement_plans_for_you_or_your_employees input[value="401(k)"]').is(':checked') == false &&
            $('.spouse_retirement_plan_do_you_have_with_your_current_employer input[value="401(k)"]').is(':checked') == false &&
            $('.spouse_retirement_plans_for_you_or_your_employees input[value="401(k)"]').is(':checked') == false) {
           /*$('.are_you_contributing_to_your_401K_plan').hide();*/
           $('.are_you_contributing_to_your_401K_plan').removeClass('enable');
           $('.are_you_contributing_to_your_401K_plan').addClass('step_disable');
           /*$('.does_your_company_provide_a_match').hide();*/
           $('.does_your_company_provide_a_match').removeClass('enable');
           $('.does_your_company_provide_a_match').addClass('step_disable');
       }
       if ( $('.self_retirement_plans_for_you_or_your_employees input[value="401(k)"]').is(':checked') == false) {
           $('.are_you_contributing_to_your_401K_plan ').removeClass('enable');
           $('.are_you_contributing_to_your_401K_plan ').addClass('step_disable');

           $('.does_your_company_provide_a_match').removeClass('enable');
           $('.does_your_company_provide_a_match').addClass('step_disable');
       }
       if ($('.spouse_retirement_plan_do_you_have_with_your_current_employer input[value="401(k)"]').is(':checked') == false) {
           $('.balance_of_your_work_sponsored_retirement_plan').removeClass('enable');
           $('.balance_of_your_work_sponsored_retirement_plan').addClass('step_disable');
       }
    }
});

var check_by_value = $( '.self_retirement_plan_do_you_have_with_your_current_employer input[value="403(b)"]' );
check_by_value.on("change", function(e) {  
    e.stopImmediatePropagation();
    if ( $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="403(b)"]').is(':checked') == true) {
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="None"]').prop("checked", false);
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="I have no idea"]').prop("checked", false);
        $('.self_balance_of_your_work_sponsored_retirement_plan').removeClass('disable');
    } else {
         if ($('.self_retirement_plan_do_you_have_with_your_current_employer input[value="401(k)"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="457"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="SIMPLE"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Profit-Sharing"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Pension"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Thrift Savings Plan (TSP)"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Other"]').is(':checked') == false
        ) {
             $('.self_balance_of_your_work_sponsored_retirement_plan').addClass('disable');
         }
    }
});

var check_by_value = $( '.self_retirement_plan_do_you_have_with_your_current_employer input[value="457"]' );
check_by_value.on("change", function(e) {  
    e.stopImmediatePropagation();
    if ( $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="457"]').is(':checked') == true) {
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="None"]').prop("checked", false);
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="I have no idea"]').prop("checked", false);
         $('.self_balance_of_your_work_sponsored_retirement_plan').removeClass('disable');
    } else {
        if ($('.self_retirement_plan_do_you_have_with_your_current_employer input[value="401(k)"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="403(b)"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="SIMPLE"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Profit-Sharing"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Pension"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Thrift Savings Plan (TSP)"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Other"]').is(':checked') == false
        ) {
             $('.self_balance_of_your_work_sponsored_retirement_plan').addClass('disable');
         }
    }
});

var check_by_value = $( '.self_retirement_plan_do_you_have_with_your_current_employer input[value="SIMPLE"]' );
check_by_value.on("change", function(e) {  
    e.stopImmediatePropagation();
    if ( $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="SIMPLE"]').is(':checked') == true) {
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="None"]').prop("checked", false);
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="I have no idea"]').prop("checked", false);
         $('.self_balance_of_your_work_sponsored_retirement_plan').removeClass('disable');
    } else {
         if ($('.self_retirement_plan_do_you_have_with_your_current_employer input[value="401(k)"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="403(b)"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="457"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Profit-Sharing"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Pension"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Thrift Savings Plan (TSP)"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Other"]').is(':checked') == false
        ) {
             $('.self_balance_of_your_work_sponsored_retirement_plan').hide();
         }
    }
});

var check_by_value = $( '.self_retirement_plan_do_you_have_with_your_current_employer input[value="Profit-Sharing"]' );
check_by_value.on("change", function(e) {  
    e.stopImmediatePropagation();
    if ( $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Profit-Sharing"]').is(':checked') == true) {
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="None"]').prop("checked", false);
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="I have no idea"]').prop("checked", false);
         $('.self_balance_of_your_work_sponsored_retirement_plan').removeClass('disable');
    } else {
         if ($('.self_retirement_plan_do_you_have_with_your_current_employer input[value="401(k)"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="403(b)"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="457"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="SIMPLE"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Pension"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Thrift Savings Plan (TSP)"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Other"]').is(':checked') == false
        ) {
             $('.self_balance_of_your_work_sponsored_retirement_plan').addClass('disable');
         }
    }
});

var check_by_value = $( '.self_retirement_plan_do_you_have_with_your_current_employer input[value="Pension"]' );
check_by_value.on("change", function(e) {  
    e.stopImmediatePropagation();
    if ( $('.self_retirement_plan_do_you_have_with_your_current_employer_pension').is(':checked') == true) {
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="None"]').prop("checked", false);
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="I have no idea"]').prop("checked", false);
         $('.self_balance_of_your_work_sponsored_retirement_plan').removeClass('disable');
    } else {
         if ($('.self_retirement_plan_do_you_have_with_your_current_employer input[value="401(k)"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="403(b)"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="457"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="SIMPLE"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Profit-Sharing"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Thrift Savings Plan (TSP)"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Other"]').is(':checked') == false
        ) {
             $('.self_balance_of_your_work_sponsored_retirement_plan').addClass('disable'); 
         }
    }
});

var check_by_value = $( '.self_retirement_plan_do_you_have_with_your_current_employer input[value="Thrift Savings Plan (TSP)"]' );
check_by_value.on("change", function(e) { 
    e.stopImmediatePropagation();
    if ( $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Thrift Savings Plan (TSP)"]').is(':checked') == true) {
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="None"]').prop("checked", false);
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="I have no idea"]').prop("checked", false);
        $('.self_balance_of_your_work_sponsored_retirement_plan').removeClass('disable');
    } else {
        if ($('.self_retirement_plan_do_you_have_with_your_current_employer input[value="401(k)"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="403(b)"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="457"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="SIMPLE"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Profit-Sharing"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Pension"]').is(':checked') == false &&
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Other"]').is(':checked') == false
        ) {
             $('.self_balance_of_your_work_sponsored_retirement_plan').addClass('disable'); 
         }
    }
});

var check_by_value = $( '.self_retirement_plan_do_you_have_with_your_current_employer input[value="None"]' );
check_by_value.on("change", function(e) { 
    e.stopImmediatePropagation();
    if ( $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="None"]').is(':checked') == true) {
        $('.self_balance_of_your_work_sponsored_retirement_plan').addClass('disable'); 
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="401(k)"]').prop("checked", false);
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="403(b)"]').prop("checked", false);
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="457"]').prop("checked", false);
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="SIMPLE"]').prop("checked", false);
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Profit-Sharing"]').prop("checked", false);
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Pension"]').prop("checked", false);
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Thrift Savings Plan (TSP)"]').prop("checked", false);
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Other"]').prop("checked", false);
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="I have no idea"]').prop("checked", false);

        if ( $('.self_retirement_plans_for_you_or_your_employees input[value="401(k)"]').is(':checked') == false &&
             $('.spouse_retirement_plan_do_you_have_with_your_current_employer  input[value="401(k)"]').is(':checked') == false &&
             $('.spouse_retirement_plans_for_you_or_your_employees input[value="401(k)"]').is(':checked') == false) {
            /*$('.are_you_contributing_to_your_401K_plan').hide();*/
            $('.are_you_contributing_to_your_401K_plan').removeClass('enable');
            $('.are_you_contributing_to_your_401K_plan').addClass('step_disable');
            /*$('.does_your_company_provide_a_match').hide();*/
            $('.does_your_company_provide_a_match').removeClass('enable');
            $('.does_your_company_provide_a_match').addClass('step_disable');
        }
        if ( $('.self_retirement_plans_for_you_or_your_employees input[value="401(k)"]').is(':checked') == false) {
            $('.are_you_contributing_to_your_401K_plan').removeClass('enable');
            $('.are_you_contributing_to_your_401K_plan').addClass('step_disable');

            $('.self_does_your_company_provide_a_match').hide();
            $('.self_does_your_company_provide_a_match').removeClass('enable');
            $('.self_does_your_company_provide_a_match').addClass('step_disable');
        }
        if ($('.spouse_retirement_plan_do_you_have_with_your_current_employer  input[value="401(k)"]').is(':checked') == false) {
            $('.balance_of_your_work_sponsored_retirement_plan').removeClass('enable');
            $('.balance_of_your_work_sponsored_retirement_plan').addClass('step_disable');
        }
    }
});

var check_by_value = $( '.self_retirement_plan_do_you_have_with_your_current_employer input[value="Other"]' );
check_by_value.on("change", function(e) { 
    e.stopImmediatePropagation();
    if ( $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Other"]').is(':checked') == true) {
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="I have no idea"]').prop("checked", false);
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="None"]').prop("checked", false);

    }
});

var check_by_value = $( '.self_retirement_plan_do_you_have_with_your_current_employer input[value="I have no idea"]' );
check_by_value.on("change", function(e) { 
    e.stopImmediatePropagation();
    if ( $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="I have no idea"]').is(':checked') == true) {
        $('.self_balance_of_your_work_sponsored_retirement_plan').addClass('disable'); 
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="401(k)"]').prop("checked", false);
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="403(b)"]').prop("checked", false);
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="457"]').prop("checked", false);
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="SIMPLE"]').prop("checked", false);
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Profit-Sharing"]').prop("checked", false);
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Pension"]').prop("checked", false);
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Thrift Savings Plan (TSP)"]').prop("checked", false);
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="Other"]').prop("checked", false);
        $('.self_retirement_plan_do_you_have_with_your_current_employer input[value="None"]').prop("checked", false);

        if ( $('.self_retirement_plans_for_you_or_your_employees input[value="401(k)"]').is(':checked') == false &&
             $('.spouse_retirement_plan_do_you_have_with_your_current_employer  input[value="401(k)"]').is(':checked') == false &&
             $('.spouse_retirement_plans_for_you_or_your_employees input[value="401(k)"]').is(':checked') == false) {
            /*$('.are_you_contributing_to_your_401K_plan').hide();*/
            $('.are_you_contributing_to_your_401K_plan').removeClass('enable');
            $('.are_you_contributing_to_your_401K_plan').addClass('step_disable');
            /*$('.does_your_company_provide_a_match').hide();*/
            $('.does_your_company_provide_a_match').removeClass('enable');
            $('.does_your_company_provide_a_match').addClass('step_disable');
        }
        if ( $('.self_retirement_plans_for_you_or_your_employees input[value="401(k)"]').is(':checked') == false) {
            $('.are_you_contributing_to_your_401K_plan').removeClass('enable');
            $('.are_you_contributing_to_your_401K_plan').addClass('step_disable');

            $('.self_does_your_company_provide_a_match').removeClass('enable');
            $('.self_does_your_company_provide_a_match').addClass('step_disable');
        }
        if ($('.spouse_retirement_plan_do_you_have_with_your_current_employer  input[value="401(k)"]').is(':checked') == false) {
            $('.balance_of_your_work_sponsored_retirement_plan').removeClass('enable');
            $('.balance_of_your_work_sponsored_retirement_plan').addClass('step_disable');
        }
    }
});



    $('.next-prev-wrap span.next-btn').click(function(e) {
        e.stopImmediatePropagation();
        var form_fieldset_all = $('fieldset.fieldset');
        var form_fieldset = $('fieldset.fieldset.active');
        var form_fieldset_enable = $('fieldset.fieldset.enable');
        var form_fieldset_done = $('fieldset.fieldset.done');
        var checkValid  = validateForm(form_fieldset );
        if( checkValid === 0 ){

            $('.webforms form fieldset.active').addClass('done');
            if (form_fieldset.next().hasClass('step_disable')) {

                form_fieldset.removeClass('active');
                while (form_fieldset.next().hasClass('step_disable') == true) {
                    form_fieldset = form_fieldset.next();
                }
                form_fieldset.next('.enable').addClass('active');

            }else{   
                form_fieldset.next().addClass('active');
                form_fieldset.before().removeClass('active');
            }
               
            $('.next-prev-wrap span.prev-btn').css('pointer-events', 'unset');        
        }
    });

    $('.next-prev-wrap span.prev-btn').click(function(e) {
        e.stopImmediatePropagation();
        var form_fieldset = $('fieldset.fieldset.active');           

        if (form_fieldset.prev().hasClass('step_disable')) {
            form_fieldset.removeClass('active');
            while (form_fieldset.prev().hasClass('step_disable') == true) {
                form_fieldset = form_fieldset.prev();
            }
            form_fieldset.prev('.done').addClass('active');
        }
        

        var get_active_id = form_fieldset.attr('id');
        if ( $('#'+get_active_id).prev().hasClass('clients_initials') ){
            $(this).css('pointer-events', 'none');
        }

        $('#'+get_active_id).prev().removeClass('done');
        $('#'+get_active_id).prev().addClass('active');
        $('#'+get_active_id).removeClass('active');
    });

    function validateForm(form_fieldset) {
        const inputFields = form_fieldset.find('input');
        let isValid = true;
    
        let isValidN = 0;
    
        inputFields.each(function() {
            const input = $(this);
            var check_is_disable = input.parents('.field').hasClass('disable');
            if(!check_is_disable){
            // Perform validation for each field
                if ( input.attr('aria-required') ) {
                    if( input.attr('type') == 'checkbox' ||  input.attr('type') == 'radio' ){
                        var required_field = '<div class="validation-advice-custom error"> Please select one of the options.  </div>';
                        var valid_by_name = input.attr('name');
                        var valid_by_name = $( 'input[name="'+valid_by_name+'"]' );
                        if ( !valid_by_name.is(':checked') ){
                            isValid = false;
                            isValidN = isValidN + 1;
                            var check_error_div = input.parents('.field[role="group"]').find('.validation-advice-custom');
                            if(!check_error_div.length){
                                input.parents('.field[role="group"]').append(required_field);
                            }
                            
                        }else{
                            isValid = true;
                            input.parents('.field').find('.validation-advice-custom').remove();
                        }
                    }
                    else if( input.attr('type') == 'text' ||  input.attr('type') == 'number' ||  input.attr('type') == 'email' ||  input.attr('type') == 'date' ){
                        var required_field = '<div class="validation-advice-custom error" > This field is required  </div>';
                        if ( input.val() == ''  ){                
                            isValid = false;
                            isValidN = isValidN + 1;
                            var check_error_div = input.parents('.field[role="group"]').find('.validation-advice-custom');
                            if(!check_error_div.length){
                                input.parents('.field[role="group"]').append(required_field);
                            }
                        }else{
                            isValid = true;
                            input.parents('.field').find('.validation-advice-custom').remove();
                        }
    
                    }
                    
                } else {
                    $('.validation-advice-custom').remove();
                }
            } //check_field_is_diable
        });
        return isValidN;
        }


//testing console log
console.log('testing file changes');
});
