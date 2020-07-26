
// dashboard
import Dashboard from "./components/dashboard/Dashboard.vue";

// developer api autentication
import Developer from "./components/Developer.vue";

// users
import User from "./components/Users.vue";

// profile 
import Profile from "./components/Profile.vue";

// course
import Course from "./components/course/Course.vue";

// student
/*import StudentCreate from "./components/student/Student.vue";*/
import Student from "./components/student/Student.vue";

// payment
import Payment from "./components/payment/Payment.vue";
import PaymentHistory from "./components/payment/PaymentHistory.vue";
import TransactionHistory from "./components/payment/TransactionHistory.vue";
import PrintInvoice from "./components/payment/invoice/Invoice.vue";
import FullPrintInvoice from "./components/payment/invoice/FullInvoice.vue";


// expenditure
import Expenture from "./components/expenditure/Expenture.vue";
// expenditure
import Message from "./components/message/Message.vue";

// report
import StudentReport from "./components/report/StudentReport.vue";
import TransactionReport from "./components/report/TransactionReport.vue";
import PaymentReport from "./components/report/PaymentReport.vue";

// setting
import Settings from "./components/settings/Settings.vue";

import NotFound from "./components/NotFound.vue";

export const routes = [
  {
    path: "/home",
    component: Dashboard
  },
  {
    path: "/dashboard",
    component: Dashboard
  },
  { 
    path: '/developer', 
    component: Developer
  },
  { 
    path: '/users', 
    component: User
  },
  { 
    path: '/profile', 
    component: Profile
  },
  { 
    path: '/courses', 
    component: Course
  },
  /*{ 
    path: '/student-create', 
    component: StudentCreate
  },*/
  { 
    path: '/students', 
    component: Student
  },
  { 
    path: '/payment',
    name: 'payment', 
    component: Payment
  },
  { 
    path: '/transaction-history', 
    name: 'TransactionHistory', 
    component: TransactionHistory
  },
  { 
    path: '/payment-history', 
    name: 'PaymentHistory', 
    component: PaymentHistory
  },
  {
    path: '/print-invoice',
    name: 'printInvoice',
    component: PrintInvoice
  },
  {
    path: '/full-print-invoice',
    name: 'fullPrintInvoice',
    component: FullPrintInvoice
  },
  {
    path: '/report-student',
    name: 'studentReport',
    component: StudentReport
  },
  {
    path: '/report-transaction',
    name: 'transactionReport',
    component: TransactionReport
  },
  {
    path: '/report-payment',
    name: 'paymentReport',
    component: PaymentReport
  },
  {
    path: '/expenditure',
    name: 'expenditure',
    component: Expenture
  },
  {
    path: '/message',
    name: 'message',
    component: Message
  },
  {
    path: '/settings',
    name: 'settings',
    component: Settings
  },
  { 
    path: '*', 
    component: NotFound
  }

];




