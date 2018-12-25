<?php

namespace SDRO\AccountBundle\Model;

class AccountUtil {

    const Dr = "dr";
    const Cr = "cr";
    //======= Account Code
    const CODE_CASH = "1000";
    const CODE_RECEIVABLE = "1200";
    const CODE_INVENTORY = "1300";
    const CODE_OWNERS_CAPITAL = "3000";    
    const CODE_REVENUE = "4000";
    const CODE_EXPENSE = "6000";
    const CODE_PAYROLL_TAXES = "7000";
    const CODE_FIT_TAXES = "7400";
    //==================== Text
    const TEXT_BEFORE_TAX = "Income before taxes";
    const TEXT_AFTER_TAX = "Net Income after taxes";
    //======= Batch
    const BATCH_CASH_CAPITAL = "10003000";
    const BATCH_SALES__CASH_RECEIVABLE = "10001200";
    const BATCH_CASH_INVENTORY = "10001300";
    const BATCH_CASH_SALARY = "10007300";
    const BATCH_BANK_CAPITAL = "10013000";
    const BATCH_BANK_ACC_RECEIVABLE = "10011200";
    const BATCH_BANK_INVENTORY = "10011300";
    const BATCH_ACC_RECEIVABLE_REVENUE = "12004000";
    const BATCH_COGS_INVENTORY = "202";
    const BATCH_RENT_PAYABLE = "203";
    const BATCH_INVENTORY_ACCOUNT_PAYABLE = "13002100";
    const BATCH_INVENTORY_INVENTORY_EXPENSE = "13006100";
    //======= Account HEAD
    const ACCOUNT_HEAD_GENERAL = "10";
    const ACCOUNT_HEAD_CASH_BANK = "20";
    const ACCOUNT_HEAD_EXPENSES = "30";
    const ACCOUNT_HEAD_CAPITAL = "40";
    const ACCOUNT_HEAD_REVENUE = "50";
    const ACCOUNT_HEAD_EMPLOYEE = "60";
    const ACCOUNT_HEAD_CUSTOMERS = "70";
    const ACCOUNT_HEAD_SUPPLIERS = "80";
    const ACCOUNT_HEAD_LOAN = "90";
    const ACCOUNT_HEAD_INVESTMENT = "100";
    //======= Account GROUP
    const ACCOUNT_GROUP_ASSETS = "10";
    const ACCOUNT_GROUP_SHORT_TERM_ASSETS = "11";
    const ACCOUNT_GROUP_LONG_TERM_ASSETS = "12";
    const ACCOUNT_GROUP_LIBILITIES = "20";
    const ACCOUNT_GROUP_EQUITY = "30";
    const ACCOUNT_GROUP_REVINUE = "40";
    const ACCOUNT_GROUP_EXPENSES = "50";
    const ACCOUNT_GROUP_PERSON = "60";
    const ACCOUNT_GROUP_SUBACCOUNT = "70";

//======= Account CODE
    const ACCOUNT_CODE_EMPLOYEE = "employee";
    const ACCOUNT_CODE_CUSTOMER = "customer";
//===========> REPORT  <=============
    const REPORT_INCOME_STATEMENT = "11";
    const REPORT_BALANCE_SHEET = "12";
    const REPORT_TRIAL_BALANCE = "13";
    const REPORT_CASH_RECEIVE_PAYMENT = "14";
    const REPORT_SALES_REPORT = "15";
    const REPORT_CASH_FLOW = "16";

//    -------- Income Statement

    public static function getIncomeStatementArray() {
        return array(
            self::CODE_REVENUE => array("name" => "Revenue", "balance" => "0"),
            self::CODE_EXPENSE => array("name" => "Expense", "balance" => "0"),
            self::TEXT_BEFORE_TAX => array("name" => self::TEXT_BEFORE_TAX, "balance" => "0"),
            self::TEXT_AFTER_TAX => array("name" => self::TEXT_AFTER_TAX, "balance" => "0"),
        );
    }

    //    -------- Balance Sheet
    public static function getBalanceSheetArray() {
        return array(
            self::CODE_REVENUE => array("name" => "Revenue", "balance" => "0"),
            self::CODE_EXPENSE => array("name" => "Expense", "balance" => "0"),
            self::TEXT_BEFORE_TAX => array("name" => self::TEXT_BEFORE_TAX, "balance" => "0"),
            self::TEXT_AFTER_TAX => array("name" => self::TEXT_AFTER_TAX, "balance" => "0"),
        );
    }

}
