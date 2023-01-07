/**
 * @brief This function is called when we want to change the date of the page
 */
function newDate() {
    var date = new Date(document.getElementById("Date").value); //get the new date in the DatePicker
    var day = date.getDate(); //get the day
    var month = date.getMonth() + 1; //get the month (add 1 because it starts at 0)
    var year = date.getFullYear(); //get the year
    if (day < 10) {
      day = "0" + day;
    } //if the day is less than 10, add a 0 before to fit with DateTime format
    if (month < 10) {
      month = "0" + month;
    } //if the month is less than 10, add a 0 before to fit with DateTime format
    dateStr = year + "-" + month + "-" + day + "T12:00:00"; //format the date fo FullCalendar
    changeDate(dateStr);
  }
  
  /**
   * @brief This function is called when we want to go to the previous day
   */
  function PreviousDay() {
    var oldDate = new Date(document.getElementById("Date").value); //get the old day in the calendar
    var newDate = new Date(
      oldDate.getFullYear(),
      oldDate.getMonth(),
      oldDate.getDate() - 1
    ); //create a new day before the old one
    var day = newDate.getDate(); //get the day
    var month = newDate.getMonth() + 1; //get the month (add 1 because it starts at 0)
    var year = newDate.getFullYear(); //get the year
    if (day < 10) {
      day = "0" + day;
    } //if the day is less than 10, add a 0 before to fit with DateTime format
    if (month < 10) {
      month = "0" + month;
    } //if the month is less than 10, add a 0 before to fit with DateTime format
    dateStr = year + "-" + month + "-" + day + "T12:00:00"; //format the date fo FullCalendar
    changeDate(dateStr);
  }
  
  /**
   * @brief This function is called when we want to go to the next day
   */
  function NextDay() {
    var oldDate = new Date(document.getElementById("Date").value); //get the old day in the calendar
    var newDate = new Date(
      oldDate.getFullYear(),
      oldDate.getMonth(),
      oldDate.getDate() + 1
    ); //create a new day after the old one
    var day = newDate.getDate(); //get the day
    var month = newDate.getMonth() + 1; //get the month (add 1 because it starts at 0)
    var year = newDate.getFullYear(); //get the year
    if (day < 10) {
      day = "0" + day;
    } //if the day is less than 10, add a 0 before to fit with DateTime format
    if (month < 10) {
      month = "0" + month;
    } //if the month is less than 10, add a 0 before to fit with DateTime format
    dateStr = year + "-" + month + "-" + day + "T12:00:00"; //format the date fo FullCalendar
    changeDate(dateStr);
  }
  
  /**
   * @brief This function is called when we want to go to the date of today
   */
  function Today() {
    var today = new Date(); //get the date of today
    var day = today.getDate(); //get the day
    var month = today.getMonth() + 1; //get the month (add 1 because it starts at 0)
    var year = today.getFullYear(); //get the year
    if (day < 10) {
      day = "0" + day;
    } //if the day is less than 10, add a 0 before to fit with DateTime format
    if (month < 10) {
      month = "0" + month;
    } //if the month is less than 10, add a 0 before to fit with DateTime format
    dateStr = year + "-" + month + "-" + day + "T12:00:00"; //format the date fo FullCalendar
    changeDate(dateStr);
  }
  
  function changeDate(dateStr){
    window.location.assign("/statistics?date=" +dateStr)
  }