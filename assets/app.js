import './bootstrap.js';
import './styles/app.css';
import $ from 'jquery';
import 'select2/dist/css/select2.min.css';
import 'select2';

document.addEventListener('DOMContentLoaded', (event) => {
  $(function () {
    $('#project_employees').select2();
  });
});
