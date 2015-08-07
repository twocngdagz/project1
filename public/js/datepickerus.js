var DatePicker,
  __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; };

DatePicker = (function() {

  function DatePicker(controls, events) {
    var self;
    this.controls = controls;
    this.events = events;
    this.update_picker_display = __bind(this.update_picker_display, this);
    this.update_picker_calendar = __bind(this.update_picker_calendar, this);
    this.update_picker = __bind(this.update_picker, this);
    self = this;
    this.picker = $('#timeControls #datePicker');
    this.picker.click(function() {
      var timespan;
      timespan = $.cookie("report_timespan" || 'day');
      $('#timeControls #picker-layout #inputSelector').datepicker('show');
      if (!timespan === 'day') return $(".ui-datepicker-month").hide();
    });
    this.pickedDate = moment().valueOf();
    $('#timeControls #picker-layout #inputSelector').change(function() {
      self.pickedDate = moment($(this).val(), "MM/DD/YYYY");
      self.pickedDate = self.pickedDate.valueOf();
      self.controls.updateRange();
      return self.events.trigger('timespanChanged');
    });
    this.DateDisplay = $('#timeControls #datePicker #Date');
    this.update_picker();
    this.events.on('timespanChanged', this.update_picker);
  }

  DatePicker.prototype.update_picker = function() {
    this.update_picker_display();
    return this.update_picker_calendar();
  };

  DatePicker.prototype.before_show = function(e, b) {
    var action, timespan;
    timespan = $.cookie("dashboard_timespan" || 'day');
    if (timespan === 'year') {
      action = 'addClass';
    } else {
      action = 'removeClass';
    }
    $('#ui-datepicker-div')[action]('hide-calendar');
    $('#ui-datepicker-div')[action]('hide-arrow-next');
    $('#ui-datepicker-div')[action]('hide-arrow-prev');
    $('#ui-datepicker-div')[action]('hide-month');
    if (timespan === 'day') {
      return $('#ui-datepicker-div').removeClass('hide-month');
    }
  };

  DatePicker.prototype.on_close = function(date, inst) {
    var datePicker, year;
    year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
    datePicker = self.dashboard.datePicker;
    self.dashboard.datePicker.pickedDate = moment(year, "YYYY");
    datePicker.controls.updateRange();
    return datePicker.events.trigger('timespanChanged');
  };

  DatePicker.prototype.update_picker_calendar = function() {
    var picker_format, timespan;
    timespan = this.controls.timespan;
    if (timespan === "day") {
      $('#timeControls #picker-layout #inputSelector').val(moment(this.pickedDate).format("MM/DD/YYYY"));
      $('#timeControls #picker-layout #inputSelector').datepicker("destroy");
      $('#timeControls #picker-layout #inputSelector').datepicker({
        beforeShow: this.before_show,
        firstDay: 1
      });
    }
    if (timespan === "week") {
      $('#timeControls #picker-layout #inputSelector').val(moment(this.pickedDate).weekday(0).format("MM/DD/YYYY"));
      $('#timeControls #picker-layout #inputSelector').datepicker("destroy");
      $('#timeControls #picker-layout #inputSelector').datepicker({
        beforeShowDay: $.datepicker.onlyMonday,
        firstDay: 1,
        beforeShow: this.before_show
      });
    }
    if (timespan === "month") {
      $('#timeControls #picker-layout #inputSelector').val(moment(this.pickedDate).format("MM/DD/YYYY"));
      $('#timeControls #picker-layout #inputSelector').datepicker("destroy");
      $('#timeControls #picker-layout #inputSelector').datepicker({
        monthpicker: true,
        beforeShow: this.before_show
      });
    }
    if (timespan === "quarter") {
      $('#timeControls #picker-layout #inputSelector').val(moment(this.pickedDate).format("MM/DD/YYYY"));
      $('#timeControls #picker-layout #inputSelector').datepicker("destroy");
      $('#timeControls #picker-layout #inputSelector').datepicker({
        quarterpicker: true,
        beforeShow: this.before_show
      });
    }
    if (timespan === "year") {
      $('#timeControls #picker-layout #inputSelector').val(moment(this.pickedDate).format("YYYY"));
      $('#timeControls #picker-layout #inputSelector').datepicker("destroy");
      picker_format = {
        dateFormat: 'YYYY',
        changeYear: true,
        beforeShow: this.before_show,
        defaultDate: new Date(moment(this.pickedDate).format("YYYY"), 1, 1),
        onClose: this.on_close
      };
      return $('#timeControls #picker-layout #inputSelector').datepicker(picker_format);
    }
  };

  DatePicker.prototype.update_picker_display = function() {
    var current_quarter, current_year, timespan;
    timespan = this.controls.timespan;
    if (timespan === "day") {
      this.DateDisplay.text("" + (moment(this.pickedDate).format("MM/DD/YYYY")));
    }
    if (timespan === "week") {
      this.DateDisplay.text("" + (moment(this.pickedDate).weekday(0).format("MM/DD/YYYY")) + " - " + (moment(this.pickedDate).weekday(7).format("MM/DD/YYYY")));
    }
    if (timespan === "month") {
      this.DateDisplay.text("" + (moment(this.pickedDate).format("MM/YYYY")));
    }
    if (timespan === "quarter") {
      current_quarter = Math.floor(moment(this.pickedDate).month() / 3) + 1;
      current_year = moment(this.pickedDate).year();
      this.DateDisplay.text("Q" + current_quarter + "/" + current_year);
    }
    if (timespan === "year") {
      return this.DateDisplay.text("" + (moment(this.pickedDate).year()));
    }
  };

  return DatePicker;

})();
