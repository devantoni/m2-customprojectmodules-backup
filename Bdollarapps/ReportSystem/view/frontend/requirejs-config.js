var config = {
    paths: {
         'datatable': 'Bdollarapps_ReportSystem/js/datatables.net',
        // 'datatableBootstrap5': 'Bdollarapps_ReportSystem/js/datatables.bootstrap5',
        // 'datatableButtons': 'Bdollarapps_ReportSystem/js/datatables.net-buttons',
        // 'buttonsBootstrap5': 'Bdollarapps_ReportSystem/js/buttons.bootstrap5.min',
        // 'jszip': 'Bdollarapps_ReportSystem/js/jszip.min',
        // 'pdfmake': 'Bdollarapps_ReportSystem/js/pdfmake.min',
        // 'vfsFonts': 'Bdollarapps_ReportSystem/js/vfs_fonts',
        // 'buttonHtml5': 'Bdollarapps_ReportSystem/js/buttons.html5.min',
        // 'buttonPrint': 'Bdollarapps_ReportSystem/js/buttons.print.min',
        // 'buttonColVis': 'Bdollarapps_ReportSystem/js/buttons.colVis.min',
        // 'datatableResponsive': 'Bdollarapps_ReportSystem/js/datatables.responsive.min',
        // 'responsiveBootstrap': 'Bdollarapps_ReportSystem/js/responsive.bootstrap5.min',
        // 'tableHTMLExport': 'Bdollarapps_ReportSystem/js/tableHTMLExport',
        'jsPDF': 'Bdollarapps_ReportSystem/js/jspdf',
        // 'jspdfAutotable': 'Bdollarapps_ReportSystem/js/jspdf.plugin.autotable.min',
        'html2canvas': 'Bdollarapps_ReportSystem/js/html2canvas.min',
    },
     shim: {
        'Bdollarapps_ReportSystem/js/chart':{
            'deps':['jquery','Bdollarapps_ReportSystem/js/charts-c3_d3.v5.min','Bdollarapps_ReportSystem/js/charts-c3_c3-chart','Bdollarapps_ReportSystem/js/chart_Chart.bundle']
        },
        'Bdollarapps_ReportSystem/js/charts': {
            'deps': ['jquery','Bdollarapps_ReportSystem/js/charts-c3_d3.v5.min','Bdollarapps_ReportSystem/js/charts-c3_c3-chart','Bdollarapps_ReportSystem/js/chart_Chart.bundle']
        }
    },
};
