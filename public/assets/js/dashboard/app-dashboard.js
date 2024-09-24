/**
 * Dashboard Analytics
 */

'use strict';
var chartInstances = {};
var chartConfig={
  'salesChart':{
    barColor:'#7367f0',
    name:'sales'
  },
  'personalCommChart':{
    barColor:'#F44336',
    name:'Personal Commission'
  },
  'directCommChart':{
    barColor:'#7367f0',
    name:'Direct Commission'
  },
  'overridingCommChart':{
    barColor:'#F44336',
    name:'Overriding Commission'
  }
};
(function () {
  let cardColor, headingColor, labelColor, shadeColor, grayColor, legendColor, borderColor;
  if (isDarkStyle) {
    cardColor = config.colors_dark.cardColor;
    labelColor = config.colors_dark.textMuted;
    headingColor = config.colors_dark.headingColor;
    shadeColor = 'dark';
    grayColor = '#5E6692'; // gray color is for stacked bar chart
    legendColor = config.colors_dark.bodyColor;
    borderColor = config.colors_dark.borderColor;
  } else {
    cardColor = config.colors.cardColor;
    labelColor = config.colors.textMuted;
    headingColor = config.colors.headingColor;
    shadeColor = '';
    grayColor = '#817D8D';
    legendColor = config.colors.bodyColor;
    borderColor = config.colors.borderColor;
  }

  var csrfToken = $('meta[name="csrf-token"]').attr('content');
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': csrfToken
    }
  });

  const currentDate = new Date();
  const currentYear = currentDate.getFullYear();
  const currentMonth = currentDate.getMonth() + 1;

  const defaultStartDate = '01/' + currentYear;
  const defaultEndDate = (currentMonth < 10 ? '0' : '') + currentMonth + '/' + currentYear;

  $('#filterStartDate').datepicker({
    autoclose: true,
    minViewMode: 1,
    format: 'mm/yyyy'
  })
  .datepicker('setDate', defaultStartDate)
  .on('changeDate', function(selected){
    var startDate = new Date(selected.date.valueOf());
    startDate.setDate(startDate.getDate(new Date(selected.date.valueOf())));
    $('#filterEndDate').datepicker('setStartDate', startDate);

    getSalesChartData('salesChart');
    getCommissionChartData('personalCommChart','Personal Commission');
    getCommissionChartData('directCommChart','Direct Commission');
    getCommissionChartData('overridingCommChart','Overriding Commission');
  });

  $('#filterEndDate').datepicker({
    autoclose: true,
    minViewMode: 1,
    format: 'mm/yyyy'
  })
  .datepicker('setDate', defaultEndDate)
  .on('changeDate', function(selected){
    var FromEndDate = new Date(selected.date.valueOf());
     FromEndDate.setDate(FromEndDate.getDate(new Date(selected.date.valueOf())));
     $('#filterStartDate').datepicker('setEndDate', FromEndDate);

     getSalesChartData('salesChart');
     getCommissionChartData('personalCommChart','Personal Commission');
     getCommissionChartData('directCommChart','Direct Commission');
     getCommissionChartData('overridingCommChart','Overriding Commission');
  });


  getSalesChartData('salesChart');
  getCommissionChartData('personalCommChart','Personal Commission');
  getCommissionChartData('directCommChart','Direct Commission');
  getCommissionChartData('overridingCommChart','Overriding Commission');

  displayLevelIndicator();

  function getSalesChartData(targetId){

    if($('#filterStartDate').val() == "" || $('#filterEndDate').val() == "")
      return false;

    $.ajax({
      url: '/app/dashboard/sales',
      type: 'POST',
      data: {
        filterStartDate:$('#filterStartDate').val(),
        filterEndDate:$('#filterEndDate').val()
      },
      success: function (response, status, xhr) {
  
        console.log(response);

        var dataMap = {};
        response.forEach(function(item) {
            dataMap[item.month_year] = parseFloat(item.total_amount);
        });

        var filterStartDate=$('#filterStartDate').val();
        var parts = filterStartDate.split('/');

        var startMonth=parseInt(parts[0]);
        var startYear=parseInt(parts[1]);
        
        var filterEndDate=$('#filterEndDate').val().replace(/\//g, '-');
        parts = filterEndDate.split('/');
        var endMonth=parseInt(parts[0]);

        var months = [
            "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
        ];

        var chartMonth=[];
        var chartData=[];

        for (var i=startMonth ; i<=endMonth ; i++){

          chartMonth.push(months[i-1]);

          var dataKey=String(i).padStart(2, '0')+'-'+startYear;

          if(dataMap[dataKey])
            chartData.push(dataMap[dataKey]);
          else  
            chartData.push(0);
        }


        displayChart(chartData,chartMonth,targetId);
  
      },
      error: function (xhr, status, error) {
  
      }
    });

  }

  function getCommissionChartData(targetId,commissionType){

    if($('#filterStartDate').val() == "" || $('#filterEndDate').val() == "")
      return false;

    $.ajax({
      url: '/app/dashboard/commission',
      type: 'POST',
      data: {
        filterStartDate:$('#filterStartDate').val(),
        filterEndDate:$('#filterEndDate').val(),
        commissionType:commissionType
      },
      success: function (response, status, xhr) {
  
        console.log(response);

        var dataMap = {};
        response.forEach(function(item) {
            dataMap[item.month_year] = parseFloat(item.total_amount);
        });

        var filterStartDate=$('#filterStartDate').val();
        var parts = filterStartDate.split('/');

        var startMonth=parseInt(parts[0]);
        var startYear=parseInt(parts[1]);
        
        var filterEndDate=$('#filterEndDate').val().replace(/\//g, '-');
        parts = filterEndDate.split('/');
        var endMonth=parseInt(parts[0]);

        var months = [
            "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
        ];

        var chartMonth=[];
        var chartData=[];

        for (var i=startMonth ; i<=endMonth ; i++){

          chartMonth.push(months[i-1]);

          var dataKey=String(i).padStart(2, '0')+'-'+startYear;

          if(dataMap[dataKey])
            chartData.push(dataMap[dataKey]);
          else  
            chartData.push(0);
        }


        displayChart(chartData,chartMonth,targetId);
  
      },
      error: function (xhr, status, error) {
  
      }
    });

  }

  function displayChart(data,categories,targetId){

    if (chartInstances[targetId]) {
      chartInstances[targetId].destroy();
    }  

    var foreColor='#373d3f';

    if(isDarkStyle){
      foreColor='#cfd3ec';
    }

    var salesChart = document.getElementById(targetId);
    var options = {
      chart: {
        type: 'bar',
        foreColor: foreColor,
        toolbar : {
          show:false
        }
      },
      series: [{
        name: chartConfig[targetId].name,
        data: data
      }],
      xaxis: {
        categories: categories
      },
      fill: {
        colors: [chartConfig[targetId].barColor, '#E91E63', '#9C27B0']
      }
    }
    
    chartInstances[targetId] = new ApexCharts(salesChart, options);
    
    chartInstances[targetId].render();
  
  }

  function displayLevelIndicator(){

    $.ajax({
      url: '/app/dashboard/level-data',
      type: 'GET',
      data: {
        
      },
      success: function (response, status, xhr) {

        var data=response.data;

        var level_container=$('.level-progress-container');

        $(level_container).append('<div class="level-progress" id="level_progress" style="width:0%;"></div>');

        var progressWidthDevider=data.length-1;

        var progressWidthIncrement=0;

        if(progressWidthDevider <= 0)
          progressWidthDevider=1;
 
        progressWidthIncrement=100/progressWidthDevider;


        var curentWidth=0;
        var nextLeveltarget=0;
        var prevCompletedtarget=0;
        data.forEach(function(item) {

          var level_id=parseInt(item.level_id);

          var activeClass=''; 
          if(level_id <= member_level_id){

            activeClass='active';

            if(level_id < member_level_id){
              
              curentWidth+=progressWidthIncrement;

            }

            prevCompletedtarget=item.level_target;
          
          }

          if( level_id > member_level_id &&  nextLeveltarget == 0){
            nextLeveltarget=item.level_target;
          }

          $(level_container).append(`<div><div class="circle ${activeClass}">${item.level_percentage}%</div><div style="text-align:center;">${item.level_target}</div></div>`);

        });


        var total_sale=response.total_sale;

        var remain_target_perc = ((total_sale - prevCompletedtarget) / (nextLeveltarget - prevCompletedtarget)) * 100;

        var current_level_completed_perc=(progressWidthIncrement*remain_target_perc/100)
  
        setTimeout(() => {
          
          $('#level_progress').css('width',(curentWidth+current_level_completed_perc)+'%');

        }, 500);
      },
      error: function (xhr, status, error) {
  
      }
    });


  }

})();
