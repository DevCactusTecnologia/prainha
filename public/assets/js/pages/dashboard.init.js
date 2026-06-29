/******/
(function(modules) { // webpackBootstrap
    /******/ // The module cache
    /******/
    var installedModules = {};
    /******/
    /******/ // The require function
    /******/
    function __webpack_require__(moduleId) {
        /******/
        /******/ // Check if module is in cache
        /******/
        if (installedModules[moduleId]) {
            /******/
            return installedModules[moduleId].exports;
            /******/
        }
        /******/ // Create a new module (and put it into the cache)
        /******/
        var module = installedModules[moduleId] = {
            /******/
            i: moduleId,
            /******/
            l: false,
            /******/
            exports: {}
            /******/
        };
        /******/
        /******/ // Execute the module function
        /******/
        modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
        /******/
        /******/ // Flag the module as loaded
        /******/
        module.l = true;
        /******/
        /******/ // Return the exports of the module
        /******/
        return module.exports;
        /******/
    }
    /******/
    /******/
    /******/ // expose the modules object (__webpack_modules__)
    /******/
    __webpack_require__.m = modules;
    /******/
    /******/ // expose the module cache
    /******/
    __webpack_require__.c = installedModules;
    /******/
    /******/ // define getter function for harmony exports
    /******/
    __webpack_require__.d = function(exports, name, getter) {
        /******/
        if (!__webpack_require__.o(exports, name)) {
            /******/
            Object.defineProperty(exports, name, { enumerable: true, get: getter });
            /******/
        }
        /******/
    };
    /******/
    /******/ // define __esModule on exports
    /******/
    __webpack_require__.r = function(exports) {
        /******/
        if (typeof Symbol !== 'undefined' && Symbol.toStringTag) {
            /******/
            Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
            /******/
        }
        /******/
        Object.defineProperty(exports, '__esModule', { value: true });
        /******/
    };
    /******/
    /******/ // create a fake namespace object
    /******/ // mode & 1: value is a module id, require it
    /******/ // mode & 2: merge all properties of value into the ns
    /******/ // mode & 4: return value when already ns object
    /******/ // mode & 8|1: behave like require
    /******/
    __webpack_require__.t = function(value, mode) {
        /******/
        if (mode & 1) value = __webpack_require__(value);
        /******/
        if (mode & 8) return value;
        /******/
        if ((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
        /******/
        var ns = Object.create(null);
        /******/
        __webpack_require__.r(ns);
        /******/
        Object.defineProperty(ns, 'default', { enumerable: true, value: value });
        /******/
        if (mode & 2 && typeof value != 'string')
            for (var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
        /******/
        return ns;
        /******/
    };
    /******/
    /******/ // getDefaultExport function for compatibility with non-harmony modules
    /******/
    __webpack_require__.n = function(module) {
        /******/
        var getter = module && module.__esModule ?
            /******/
            function getDefault() { return module['default']; } :
            /******/
            function getModuleExports() { return module; };
        /******/
        __webpack_require__.d(getter, 'a', getter);
        /******/
        return getter;
        /******/
    };
    /******/
    /******/ // Object.prototype.hasOwnProperty.call
    /******/
    __webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
    /******/
    /******/ // __webpack_public_path__
    /******/
    __webpack_require__.p = "/";
    /******/
    /******/
    /******/ // Load entry module and return exports
    /******/
    return __webpack_require__(__webpack_require__.s = 4);
    /******/
})
/************************************************************************/
/******/
({

    /***/
    "./resources/js/pages/dashboard.init.js":
    /*!**********************************************!*\
      !*** ./resources/js/pages/dashboard.init.js ***!
      \**********************************************/
    /*! no static exports found */
    /***/
        (function(module, exports) {

        /*
        Product Name: Sislac - Sistema para Laboratórios, Clinicas e Hospitais
        Author: Themesbrand
        Version: 1.0.0
        Website: https://themesbrand.com/
        Contact: support@themesbrand.com
        File: Dashboard Init Js File
        */

        // Mixed Chart

        if (document.querySelector("#monthly_users")) {


            $(document).ready(function() {

                var total_appointment = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
                var total_exams = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

                $.ajax({

                    type: 'GET',
                    url: 'getMonthlyUsersRevenue',
                    dataType: 'json',
                    success: function(data) {

                        // console.log(data.total_patient);
                        var i;
                        for (i = 0; i < 12; i++) {

                            if (data.total_appointment[i] !== undefined) {
                                // data.total_patient;

                                total_appointment.splice(data.total_appointment[i].Month - 1, 1, data.total_appointment[i].total_appointment);

                            }

                            if (data.total_exams[i] !== undefined) {

                                total_exams.splice(data.total_exams[i].Month - 1, 1, data.total_exams[i].total_exams);

                            }

                        }

                        //console.log(total_patient);

                        var options = {
                            series: [{
                                name: 'Atendimentos',
                                type: 'column',
                                data: total_appointment
                            }, {
                                name: 'Exames',
                                type: 'line',
                                data: total_exams
                            }],
                            chart: {
                                height: 350,
                                type: 'line',
                                toolbar: {
                                    show: false
                                }
                            },
                            stroke: {
                                width: [0, 4]
                            },
                            //labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                            xaxis: {
                                //type: 'datetime'
                                categories: ['jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez']
                            },
                            yaxis: [{
                                title: {
                                    text: 'No. de Atendimentos',
                                },

                            }, {
                                opposite: true,
                                title: {
                                    text: ' (milhares)'
                                }
                            }]
                        };

                        var monthly_user_chart = new ApexCharts(document.querySelector("#monthly_users"), options);
                        monthly_user_chart.render();

                    },
                    error: function(data) {
                        console.log('opa! Alguma coisa deu errado!!!');
                    }

                });

            });

        }
        // Column Chart

        if (document.querySelector("#monthly_appointment")) {

            $(document).ready(function() {

                var total_appointment = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];

                $.ajax({

                    type: 'GET',
                    url: 'getMonthlyAppointments',
                    dataType: 'json',
                    success: function(data) {
                        console.log(data);
                        var i;
                        for (i = 0; i < 12; i++) {

                            if (data[i] !== undefined) {

                                total_appointment.splice(data[i].Month - 1, 1, data[i].total_appointment);

                            }

                        }

                        var options = {
                            chart: {
                                height: 350,
                                type: 'bar',
                                toolbar: {
                                    show: false
                                }
                            },
                            plotOptions: {
                                bar: {
                                    horizontal: false,
                                    columnWidth: '45%',
                                    endingShape: 'rounded'
                                }
                            },
                            dataLabels: {
                                enabled: false
                            },
                            stroke: {
                                show: true,
                                width: 2,
                                colors: ['transparent']
                            },
                            series: [{
                                name: 'No. of Appointments',
                                data: total_appointment
                            }],
                            colors: ['#34c38f'],
                            xaxis: {
                                categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                            },
                            yaxis: {
                                title: {
                                    text: 'No. of Appointments'
                                }
                            },
                            grid: {
                                borderColor: '#f1f1f1'
                            },
                            fill: {
                                opacity: 1
                            },
                            tooltip: {
                                y: {
                                    formatter: function formatter(val) {
                                        return val;
                                    }
                                }
                            }
                        };

                        var monthly_appointment_chart = new ApexCharts(document.querySelector("#monthly_appointment"), options);
                        monthly_appointment_chart.render();

                    },
                    error: function(data) {
                        console.log('oops! Something Went Wrong!!!');
                    }

                });

            });

        }

        // Radial chart

        $.ajax({

            type: 'GET',
            url: 'getMonthlyEarning',
            dataType: 'json',
            success: function(data) {

                //console.log(data);

                var options = {
                    chart: {
                        height: 200,
                        type: 'radialBar',
                        offsetY: -10
                    },
                    plotOptions: {
                        radialBar: {
                            startAngle: -135,
                            endAngle: 135,
                            dataLabels: {
                                name: {
                                    fontSize: '13px',
                                    color: undefined,
                                    offsetY: 60
                                },
                                value: {
                                    offsetY: 22,
                                    fontSize: '16px',
                                    color: undefined,
                                    formatter: function formatter(val) {
                                        return val + "%";
                                    }
                                }
                            }
                        }
                    },
                    colors: ['#556ee6'],
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shade: 'dark',
                            shadeIntensity: 0.15,
                            inverseColors: false,
                            opacityFrom: 1,
                            opacityTo: 1,
                            stops: [0, 50, 65, 91]
                        }
                    },
                    stroke: {
                        dashArray: 4
                    },
                    series: [data['diff']],
                    labels: ['Monthly Analytics']
                };
                var chart = new ApexCharts(document.querySelector("#radialBar-chart"), options);
                chart.render();

            },
            error: function(data) {
                console.log('oops! Something Went Wrong!!!');
            }

        });
        $('.per-page-items').click(function(e) {
            var token = $("input[name='_token']").val();
            var page = $(this).data("page");
            $('.per-page-items').each(function() {
                $(this).removeClass('btn-primary').addClass('btn-info');
            });
            $(this).removeClass('btn-info').addClass('btn-primary');

            $.ajax({
                url: "per-page-item",
                type: 'POST',
                data: {
                    "page": page,
                    "_token": token,
                },
                success: function(response) {
                    // console.log(response);
                    toastr.success(response.Message);
                },
                error: function(response) {
                    toastr.error(response.responseJSON.message);
                }
            });

        });

        /***/
    }),

    /***/
    4:
    /*!****************************************************!*\
      !*** multi ./resources/js/pages/dashboard.init.js ***!
      \****************************************************/
    /*! no static exports found */
    /***/
        (function(module, exports, __webpack_require__) {

        module.exports = __webpack_require__( /*! E:\Laravel Projects\Sislac\resources\js\pages\dashboard.init.js */ "./resources/js/pages/dashboard.init.js");


        /***/
    })

    /******/
});