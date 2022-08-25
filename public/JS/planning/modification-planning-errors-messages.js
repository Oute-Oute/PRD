/**
 * @brief This function update list error messages 
 */
 function updateErrorMessages() {
    var listScheduledActivities = calendar.getEvents(); //recover all events from the calendar
  
    listErrorMessages.messageUnscheduledAppointment = [];
    var listAppointments = JSON.parse(document.getElementById("listeAppointments").value.replaceAll("3aZt3r", " ")); //recover all appointments
    listAppointments.forEach((currentAppointment) => { //browse all appointments
      var unscheduledAppointment = true;
      listScheduledActivities.forEach((scheduledActivity) => { //browse all events
        if (currentAppointment.id == scheduledActivity._def.extendedProps.appointment) { //if the appointment is already on the planning
          //we don't set an error message
          unscheduledAppointment = false;
        }
      })
      if (unscheduledAppointment == true) { //if the appointment is not already on the planning
        //we set an error message
        var message = "Le rendez-vous de " + currentAppointment.idPatient[0].lastname + " " + currentAppointment.idPatient[0].firstname + " pour le parcours " + currentAppointment.idPathway[0].title + " n'est pas encore planifié.";
        listErrorMessages.messageUnscheduledAppointment.push(message);
      }
    })
  
    listErrorMessages.listScheduledAppointment = [];
  
    //browse all events
    listScheduledActivities.forEach((scheduledActivity) => {
      if (scheduledActivity.display != "background") { //check if the scheduled activity is not an unavailability
        var appointmentAlreadyExist = false;
        if (listErrorMessages.listScheduledAppointment != []) { //check if list error messages is not empty
          listErrorMessages.listScheduledAppointment.forEach((errorMessage) => { //browse the list error messages
            if (scheduledActivity._def.extendedProps.appointment == errorMessage.appointmentId) { //check if the appointment is already register in the list
              appointmentAlreadyExist = true;
  
              //set the error messages for earliest and latest appointment time
              errorMessage.messageEarliestAppointmentTime = getMessageEarliestAppointmentTime(listScheduledActivities, scheduledActivity._def.extendedProps.appointment);
              errorMessage.messageLatestAppointmentTime = getMessageLatestAppointmentTime(listScheduledActivities, scheduledActivity._def.extendedProps.appointment);
  
              var scheduledActivityAlreadyExist = false;
              errorMessage.listScheduledActivity.forEach((existingScheduledActivity) => { //browse the scheduled activities related to the appointment
                if (existingScheduledActivity.scheduledActivityId == scheduledActivity._def.publicId) { //if the scheduled activity already exist in the list
                  scheduledActivityAlreadyExist = true;
  
                  //update the data
                  existingScheduledActivity.messageNotFullyScheduled = getMessageNotFullyScheduled(scheduledActivity), //set error message for not fully scheduled
                    existingScheduledActivity.messageAppointmentActivityAlreadyScheduled = getMessageAppointmentActivityAlreadyScheduled(listScheduledActivities, scheduledActivity);
                  existingScheduledActivity.messageDelay = getMessageDelay(listScheduledActivities, scheduledActivity); //set error message for delay
                  existingScheduledActivity.listCategoryHumanResources = getListCategoryHumanResources(scheduledActivity); //set data for category human resources
                  existingScheduledActivity.listHumanResources = getListHumanResources(scheduledActivity); //set data for human resources
                  existingScheduledActivity.listCategoryMaterialResources = getListCategoryMaterialResources(scheduledActivity); //set data for category material resources
                  existingScheduledActivity.listMaterialResources = getListMaterialResources(scheduledActivity); //set data for material resources
                }
              })
              if (scheduledActivityAlreadyExist == false) { //if the scheduled activity doesn't exist
                //add new scheduled activity in the list
                errorMessage.listScheduledActivity.push({
                  //add data for the scheduled activity
                  scheduledActivityId: scheduledActivity._def.publicId,
                  scheduledActivityName: scheduledActivity._def.title,
  
                  messageNotFullyScheduled: getMessageNotFullyScheduled(scheduledActivity), //add error message for not fully scheduled
                  messageAppointmentActivityAlreadyScheduled: getMessageAppointmentActivityAlreadyScheduled(listScheduledActivities, scheduledActivity),
                  messageDelay: getMessageDelay(listScheduledActivities, scheduledActivity), //add error message for delay 
                  listCategoryHumanResources: getListCategoryHumanResources(scheduledActivity), //add data for category human resources
                  listHumanResources: getListHumanResources(scheduledActivity), //add data for human resource
                  listCategoryMaterialResources: getListCategoryMaterialResources(scheduledActivity), //add data for category material resources
                  listMaterialResources: getListMaterialResources(scheduledActivity) //add data for material resource
                })
              }
            }
          })
        }
        if (appointmentAlreadyExist == false) { //if the appointment doesn't exist
          //add new appointment
          listErrorMessages.listScheduledAppointment.push({
            //add data for the appointment
            appointmentId: scheduledActivity._def.extendedProps.appointment,
            patientName: scheduledActivity._def.extendedProps.patient,
            pathwayName: scheduledActivity._def.extendedProps.pathway,
  
            //add error message for earliest and latest appointment time
            messageEarliestAppointmentTime: getMessageEarliestAppointmentTime(listScheduledActivities, scheduledActivity._def.extendedProps.appointment),
            messageLatestAppointmentTime: getMessageLatestAppointmentTime(listScheduledActivities, scheduledActivity._def.extendedProps.appointment),
  
            //add the new scheduled activity
            listScheduledActivity: [{
              //add data for the scheduled activity
              scheduledActivityId: scheduledActivity._def.publicId,
              scheduledActivityName: scheduledActivity._def.title,
  
              messageNotFullyScheduled: getMessageNotFullyScheduled(scheduledActivity), //add error message for not fully scheduled
              messageAppointmentActivityAlreadyScheduled: getMessageAppointmentActivityAlreadyScheduled(listScheduledActivities, scheduledActivity),
              messageDelay: getMessageDelay(listScheduledActivities, scheduledActivity), //add error message for delay
              listCategoryHumanResources: getListCategoryHumanResources(scheduledActivity), //add data for category human resources
              listHumanResources: getListHumanResources(scheduledActivity), //add data for human resource
              listCategoryMaterialResources: getListCategoryMaterialResources(scheduledActivity), //add data for category material resources
              listMaterialResources: getListMaterialResources(scheduledActivity) //add data for material resource
            }]
          })
        }
      }
    })
    updatePanelErrorMessages(); //update the panel error messages
  }
  
  /**
   * @brief This function return an error message if the appointment starts too early, return "" if we have no problem.
   * @param {*} listScheduledActivities list of all calendar events
   * @param {*} appointmentId appointment id require
   * @returns the error message
   */
  function getMessageEarliestAppointmentTime(listScheduledActivities, appointmentId) {
    var message = [];
  
    var listeAppointments = JSON.parse(document.getElementById("listeAppointments").value.replaceAll("3aZt3r", " ")); //recover list appointments
    var appointment;
    listeAppointments.forEach((currentAppointment) => { //browse the list appointments
      if (currentAppointment.id == appointmentId) { //if we find the appointment linked to the identifier
        appointment = currentAppointment //set the appointment for get all his data
      }
    })
    let earliestAppointmentDate = new Date(currentDateStr.split("T")[0] + " " + appointment.earliestappointmenttime.split("T")[1]);
  
    listScheduledActivities.forEach((scheduledActivity) => { //browse all events
      if (scheduledActivity._def.extendedProps.appointment == appointmentId) { //if the scheduled activity is related to the appointment
        //we check if the start time is earlier than the earliest appointment time
        if (new Date(scheduledActivity.start.getTime() - 2 * 60 * 60 * 1000) < earliestAppointmentDate) {
          //if it's earlier, we set an error message
          message.push(scheduledActivity._def.title + " commence avant " + earliestAppointmentDate.getHours().toString().padStart(2, "0") + ":" + earliestAppointmentDate.getMinutes().toString().padStart(2, "0") + " qui est l'heure d'arrivée au plus tôt du patient. ");
        }
      }
    })
  
    return message;
  }
  
  /**
   * @brief This function return an error message if the appointment end too late, return "" if we have no problem.
   * @param {*} listScheduledActivities list of all calendar events
   * @param {*} appointmentId appointment id require
   * @returns the error message
   */
  function getMessageLatestAppointmentTime(listScheduledActivities, appointmentId) {
    var message = [];
  
    var listeAppointments = JSON.parse(document.getElementById("listeAppointments").value.replaceAll("3aZt3r", " ")); //recover list appointments
    var appointment;
    listeAppointments.forEach((currentAppointment) => { //browse the list appointments
      if (currentAppointment.id == appointmentId) { //if we find the appointment linked to the identifier
        appointment = currentAppointment //set the appointment for get all his data
      }
    })
    let latestAppointmentDate = new Date(currentDateStr.split("T")[0] + " " + appointment.latestappointmenttime.split("T")[1]);
  
    listScheduledActivities.forEach((scheduledActivity) => { //browse all events
      if (scheduledActivity._def.extendedProps.appointment == appointmentId) { //if the scheduled activity is related to the appointment
        //we check if the end time is later than the latest appointment time
        if (new Date(scheduledActivity.end.getTime() - 2 * 60 * 60 * 1000) > latestAppointmentDate) {
          //if it's later, we set an error message
          message.push(scheduledActivity._def.title + " finit après " + latestAppointmentDate.getHours().toString().padStart(2, "0") + ":" + latestAppointmentDate.getMinutes().toString().padStart(2, "0") + " qui est l'heure de fin au plus tard du patient. ");
        }
      }
    })
  
    return message;
  }
  
  /**
   * 
   * @param {*} scheduledActivity 
   * @returns 
   */
  function getMessageNotFullyScheduled(scheduledActivity) {
    var message = "";
    scheduledActivity._def.resourceIds.forEach((resource) => {
      console.log(scheduledActivity)
      console.log(scheduledActivity._def.extendedProps.categoryHumanResource)
      if (resource == "h-default" && scheduledActivity._def.extendedProps.categoryHumanResource[0].quantity>0|| resource == "m-default" && scheduledActivity._def.extendedProps.categoryMaterialResource[0].quantity>0) {
        message = "L'activité n'a pas été allouée à toutes les resources dont elle a besoin."
      }
    })
  
  
    return message;
  }
  
  /**
   * 
   */
  function getMessageAppointmentActivityAlreadyScheduled(listScheduledActivities, scheduledActivity) {
    var messages = [];
  
    listScheduledActivities.forEach((appointmentScheduledActivity) => {
      if (appointmentScheduledActivity._def.extendedProps.appointment == scheduledActivity._def.extendedProps.appointment && scheduledActivity._def.publicId != appointmentScheduledActivity._def.publicId) {
        if ((scheduledActivity.start > appointmentScheduledActivity.start && scheduledActivity.start < appointmentScheduledActivity.end) || (scheduledActivity.end > appointmentScheduledActivity.start && scheduledActivity.end < appointmentScheduledActivity.end) || (scheduledActivity.start <= appointmentScheduledActivity.start && scheduledActivity.end >= appointmentScheduledActivity.end)) {
          messages.push(appointmentScheduledActivity.title + " est déjà programé sur le même créneau.");
        }
      }
    })
  
    return messages;
  }
  
  /**
   * @brief This function return error messages if the delay between the scheduled activity and one of its successors was not good, 
   * return [] if we have no problem.
   * @param {*} listScheduledActivities list of all calendar events
   * @param {*} scheduledActivity scheduled activity verified
   * @returns the error message
   */
  function getMessageDelay(listScheduledActivities, scheduledActivity) {
    var messages = [];
  
    var listSuccessors = JSON.parse(document.getElementById("listeSuccessors").value); //recover list successors
  
    listSuccessors.forEach((successor) => { //browse all successors
      if (successor.idactivitya == scheduledActivity._def.extendedProps.activity) { //if the scheduled activity has a successor
        listScheduledActivities.forEach((scheduledActivityB) => { //browse all events
          if (scheduledActivityB._def.extendedProps.appointment == scheduledActivity._def.extendedProps.appointment) {
            if (successor.idactivityb == scheduledActivityB._def.extendedProps.activity) { //if the scheduled activity check is a successor of the scheduled activity verified
              //we check the delay between the two scheduled activities
              var duration = (scheduledActivityB.start.getTime() - scheduledActivity.end.getTime()) / (60 * 1000);
              if (duration < successor.delaymin) {
                //if the delay is shorter, we set an error message
                var message = "";
                if (duration < 0) {
                  duration = duration * (-1);
                  if (successor.delaymin == 0) {
                    message = scheduledActivityB._def.title + " commence " + duration + " minutes avant la fin de " + scheduledActivity._def.title + " alors qu'elle devrait commencer après.";
                  }
                  else {
                    message = scheduledActivityB._def.title + " commence " + duration + " minutes avant la fin de " + scheduledActivity._def.title + " alors qu'elle devrait commencer au minimum " + successor.delaymin + " minutes après.";
                  }
                }
                else {
                  if (duration == 0) {
                    message = "Il n'y a pas de délai entre " + scheduledActivity._def.title + " et " + scheduledActivityB._def.title + " alors qu'il devrait être au minimum de " + successor.delaymin + " minutes.";
                  }
                  else {
                    message = "Le delay entre " + scheduledActivity._def.title + " et " + scheduledActivityB._def.title + " est de " + duration + " minutes ce qui est inférieur au délai minimum de " + successor.delaymin + " minutes.";
                  }
                }
                messages.push(message);
              }
              if (duration > successor.delaymax) {
                //if the delay is longer, we set an error message
                var message = "Le delay entre " + scheduledActivity._def.title + " et " + scheduledActivityB._def.title + " est de " + duration + " minutes ce qui est supèrieur au délai maximum de " + successor.delaymax + " minutes.";
                messages.push(message);
              }
            }
          }
        })
      }
    })
  
    return messages;
  }
  
  /**
   * @brief This function return all category related to the human resources related to the scheduled activity, 
   * return [] if we have no human resources.
   * @param {*} scheduledActivity scheduled activity verified
   * @returns the list of category human resources related to the scheduled activity
   */
  function getListCategoryHumanResources(scheduledActivity) {
    var listCategoryHumanResources = [];
  
    //recover all relation between categories and human resources
    var listCategoryOfHumanResources = JSON.parse(document.getElementById("categoryOfHumanResourceJSON").value.replaceAll("3aZt3r", " "));
  
    scheduledActivity._def.resourceIds.forEach((humanResource) => { //browse all resources related to the scheduled activity
      if (humanResource.substring(0, 5) == "human") { //check only the human resources
        var listCategoryOfHumanResource = [];
        var listWrongCategoriesOfHumanResource = [];
        var countValidCategory = 0;
        listCategoryOfHumanResources.forEach((categoryOfHumanResource) => { //browse the relations between categories and human resources 
          if (categoryOfHumanResource.idresource == humanResource) { //if we find a category of the human resource 
            if (getMessageWrongCategory(scheduledActivity, categoryOfHumanResource.idcategory, "human") == "") {
              listCategoryOfHumanResource.push(categoryOfHumanResource);
              countValidCategory++;
            }
            else {
              listWrongCategoriesOfHumanResource.push(categoryOfHumanResource);
            }
          }
        })
  
        if (countValidCategory == 0) {
          listWrongCategoriesOfHumanResource.forEach((categoryOfHumanResource) => {
            listCategoryOfHumanResource.push(categoryOfHumanResource);
          })
        }
  
        listCategoryOfHumanResource.forEach((categoryOfHumanResource) => {
          var categoryHumanResourceAlreadyExist = false;
          if (listCategoryHumanResources != []) {
            listCategoryHumanResources.forEach((categoryHumanResource) => {
              if (categoryHumanResource.categoryHumanResourceId == categoryOfHumanResource.idcategory) { //if the category already exist in the list
                categoryHumanResourceAlreadyExist = true;
  
                //we set error messages for the quantity of human resources and if it's a wrong category
                categoryHumanResource.messageCategoryQuantity = getMessageCategoryQuantity(scheduledActivity, categoryOfHumanResource.idcategory, "human");
                categoryHumanResource.messageWrongCategory = getMessageWrongCategory(scheduledActivity, categoryOfHumanResource.idcategory, "human");
              }
            })
          }
          if (categoryHumanResourceAlreadyExist == false) { //if the category doesn't exist in the list
            //add the new category and the new human resource
            listCategoryHumanResources.push({
              categoryHumanResourceId: categoryOfHumanResource.idcategory,
              messageCategoryQuantity: getMessageCategoryQuantity(scheduledActivity, categoryOfHumanResource.idcategory, "human"),
              messageWrongCategory: getMessageWrongCategory(scheduledActivity, categoryOfHumanResource.idcategory, "human"),
            })
          }
        })
      }
    })
  
    return listCategoryHumanResources;
  }
  
  /**
   * @brief This function return all human resources related to the scheduled activity, 
   * return [] if we have no human resources.
   * @param {*} scheduledActivity scheduled activity verified
   * @returns the list of human resources related to the scheduled activity
   */
  function getListHumanResources(scheduledActivity) {
    var listHumanResources = [];
  
    scheduledActivity._def.resourceIds.forEach((humanResource) => { //browse all resources related to the scheduled activity
      if (humanResource.substring(0, 5) == "human") { //check only the human resources
        var humanResourceAlreadyExist = false;
        if (listHumanResources != []) {
          listHumanResources.forEach((existingHumanResource) => {
            if (existingHumanResource.humanResourceId == humanResource) { //we check if the human resource is already present on the list
              humanResourceAlreadyExist = true;
              //if it's already present, we set the error messages for working hours, unavailability and if the resource is already scheduled in an other activity
              existingHumanResource.messageWorkingHours = getMessageWorkingHours(scheduledActivity, humanResource);
              existingHumanResource.messageUnavailability = getMessageUnavailability(scheduledActivity, humanResource);
              existingHumanResource.messageAlreadyScheduled = getMessageAlreadyExist(scheduledActivity, humanResource);
            }
          })
        }
        if (humanResourceAlreadyExist == false) { //if the human resource doesn't exist in the list
          //add new human resource
          listHumanResources.push({
            humanResourceId: humanResource,
            humanResourceName: getResourceTitle(humanResource),
            messageWorkingHours: getMessageWorkingHours(scheduledActivity, humanResource),
            messageUnavailability: getMessageUnavailability(scheduledActivity, humanResource),
            messageAlreadyScheduled: getMessageAlreadyExist(scheduledActivity, humanResource)
          })
        }
      }
    });
  
    return listHumanResources;
  }
  
  /**
   * @brief This function return all category related to the material resources related to the scheduled activity, 
   * return [] if we have no material resources.
   * @param {*} scheduledActivity scheduled activity verified
   * @returns the list of category material resources related to the scheduled activity
   */
  function getListCategoryMaterialResources(scheduledActivity) {
    var listCategoryMaterialResources = [];
  
    //recover all relation between categories and material resources
    var listCategoryOfMaterialResources = JSON.parse(document.getElementById("categoryOfMaterialResourceJSON").value.replaceAll("3aZt3r", " "));
  
    scheduledActivity._def.resourceIds.forEach((materialResource) => { //browse all resources related to the scheduled activity
      if (materialResource.substring(0, 8) == "material") { //check only the material resources
        var listCategoryOfMaterialResource = [];
        var listWrongCategoriesOfMaterialResource = [];
        var countValidCategory = 0;
        listCategoryOfMaterialResources.forEach((categoryOfMaterialResource) => { //browse the relations between categories and material resources 
          if (categoryOfMaterialResource.idresource == materialResource) { //if we find a category of the material resource 
            if (getMessageWrongCategory(scheduledActivity, categoryOfMaterialResource.idcategory, "material") == "") {
              listCategoryOfMaterialResource.push(categoryOfMaterialResource);
              countValidCategory++;
            }
            else {
              listWrongCategoriesOfMaterialResource.push(categoryOfMaterialResource);
            }
          }
        })
  
        if (countValidCategory == 0) {
          listWrongCategoriesOfMaterialResource.forEach((categoryOfMaterialResource) => {
            listCategoryOfMaterialResource.push(categoryOfMaterialResource);
          })
        }
  
        listCategoryOfMaterialResource.forEach((categoryOfMaterialResource) => {
          var categoryMaterialResourceAlreadyExist = false;
          if (listCategoryMaterialResources != []) {
            listCategoryMaterialResources.forEach((categoryMaterialResource) => {
              if (categoryMaterialResource.categoryMaterialResourceId == categoryOfMaterialResource.idcategory) { //if the category already exist in the list
                categoryMaterialResourceAlreadyExist = true;
  
                //we set error messages for the quantity of material resources and if it's a wrong category
                categoryMaterialResource.messageCategoryQuantity = getMessageCategoryQuantity(scheduledActivity, categoryOfMaterialResource.idcategory, "material");
                categoryMaterialResource.messageWrongCategory = getMessageWrongCategory(scheduledActivity, categoryOfMaterialResource.idcategory, "material");
              }
            })
          }
          if (categoryMaterialResourceAlreadyExist == false) { //if the category doesn't exist in the list
            //add the new category and the new material resource
            listCategoryMaterialResources.push({
              categoryMaterialResourceId: categoryOfMaterialResource.idcategory,
              messageCategoryQuantity: getMessageCategoryQuantity(scheduledActivity, categoryOfMaterialResource.idcategory, "material"),
              messageWrongCategory: getMessageWrongCategory(scheduledActivity, categoryOfMaterialResource.idcategory, "material"),
            })
          }
        })
      }
    })
  
    return listCategoryMaterialResources;
  }
  
  /**
   * @brief This function return all material resources related to the scheduled activity, 
   * return [] if we have no material resources.
   * @param {*} scheduledActivity scheduled activity verified
   * @returns the list of material resources related to the scheduled activity
   */
  function getListMaterialResources(scheduledActivity) {
    var listMaterialResources = [];
  
    scheduledActivity._def.resourceIds.forEach((materialResource) => { //browse all resources related to the scheduled activity
      if (materialResource.substring(0, 8) == "material") { //check only the material resources
        var materialResourceAlreadyExist = false;
        if (listMaterialResources != []) {
          listMaterialResources.forEach((existingMaterialResource) => {
            if (existingMaterialResource.materialResourceId == materialResource) { //we check if the material resource is already present on the list
              materialResourceAlreadyExist = true;
              //if it's already present, we set the error messages for working hours, unavailability and if the resource is already scheduled in an other activity
              existingMaterialResource.messageWorkingHours = getMessageWorkingHours(scheduledActivity, materialResource);
              existingMaterialResource.messageUnavailability = getMessageUnavailability(scheduledActivity, materialResource);
              existingMaterialResource.messageAlreadyScheduled = getMessageAlreadyExist(scheduledActivity, materialResource);
            }
          })
        }
        if (materialResourceAlreadyExist == false) { //if the material resource doesn't exist in the list
          //add new material resource
          listMaterialResources.push({
            materialResourceId: materialResource,
            materialResourceName: getResourceTitle(materialResource),
            messageWorkingHours: getMessageWorkingHours(scheduledActivity, materialResource),
            messageUnavailability: getMessageUnavailability(scheduledActivity, materialResource),
            messageAlreadyScheduled: getMessageAlreadyExist(scheduledActivity, materialResource)
          })
        }
      }
    });
  
    return listMaterialResources;
  }
  
  /**
   * @brief This function return the resource name
   * @param {*} resourceId resource identifier verified
   * @returns resource name
   */
  function getResourceTitle(resourceId) {
    var listResources;
    if (resourceId.substring(0, 5) == "human") { //set the list resources if it's a human resource
      listResources = JSON.parse(document.getElementById("human").value.replaceAll("3aZt3r", " "));
    }
    else { //set the list resources if it's a material resource
      listResources = JSON.parse(document.getElementById("material").value.replaceAll("3aZt3r", " "));
    }
  
    var resourceName = "undefined";
  
    listResources.forEach((resource) => { //browse all resources
      if (resource.id == resourceId) { //if we find the resource
        //we set the resource name
        resourceName = resource.title;
      }
    })
  
    return resourceName;
  }
  
  /**
   * @brief This function return an error message if the quantity of resource from the category is superior than necessary, 
   * return "" if we have no problem
   * @param {*} scheduledActivity scheduled activity verified
   * @param {*} categoryResourceId category identifier verified
   * @param {*} typeResources type of the resource : material or human
   * @returns error message
   */
  function getMessageCategoryQuantity(scheduledActivity, categoryResourceId, typeResources) {
    var message = "";
  
    if (getMessageWrongCategory(scheduledActivity, categoryResourceId, typeResources) == "") { //we can check the quantity only if it's not awring category
      var listCategoryOfResources;
      if (typeResources == "human") { //set the list category resources if it's a human resource
        listCategoryOfResources = JSON.parse(document.getElementById("categoryOfHumanResourceJSON").value.replaceAll("3aZt3r", " "));
      }
      else { //set the list category resources if it's a material resource
        listCategoryOfResources = JSON.parse(document.getElementById("categoryOfMaterialResourceJSON").value.replaceAll("3aZt3r", " "));
      }
  
      var categoryQuantity = 0;
      listCategoryOfResources.forEach((categoryOfResource) => { //browse all category of resources
        if (categoryOfResource.idcategory == categoryResourceId) { //if we find the good category
          scheduledActivity._def.resourceIds.forEach((scheduledActivityResource) => { //browse all resources related to the scheduled activity
            if (scheduledActivityResource == categoryOfResource.idresource) { //if the resource scheduled is from the category
              //we add to the quantity 1
              categoryQuantity++;
            }
          })
        }
      })
  
      if (typeResources == "human") { //if the category type is human
        scheduledActivity._def.extendedProps.categoryHumanResource.forEach((categoryHumanResource) => { //browse all category human resources related to the scheduled activity
          if (categoryHumanResource.id == categoryResourceId) { //if it's the good category
            if (categoryHumanResource.quantity < categoryQuantity) { //if the quantity is superior of necessary
              //we set error message
              message = scheduledActivity.title + " à " + categoryQuantity + " " + categoryHumanResource.categoryname + " alors qu'il n'en suffit que de " + categoryHumanResource.quantity + " .";
            }
          }
        })
      }
      else { //if the category type is material
        scheduledActivity._def.extendedProps.categoryMaterialResource.forEach((categoryMaterialResource) => { //browse all category material resources related to the scheduled activity
          if (categoryMaterialResource.id == categoryResourceId) { //if it's the good category
            if (categoryMaterialResource.quantity < categoryQuantity) { //if the quantity is superior of necessary
              //we set error message
              message = scheduledActivity.title + " à " + categoryQuantity + " " + categoryMaterialResource.categoryname + " alors qu'il n'en suffit que de " + categoryMaterialResource.quantity + " .";
            }
          }
        })
      }
    }
  
    return message;
  }
  
  /**
   * @brief This function return an error message if the category of resource is not necessary, 
   * return "" if we have no problem
   * @param {*} scheduledActivity scheduled activity verified
   * @param {*} categoryResourceId category identifier verified
   * @param {*} typeResources type of the resource : material or human
   * @returns error message
   */
  function getMessageWrongCategory(scheduledActivity, categoryResourceId, typeResources) {
    var message = "";
  
    var categoryExist = false;
    var categoryName = "";
    if (typeResources == "human") { //if the resource is human
      scheduledActivity._def.extendedProps.categoryHumanResource.forEach((categoryHumanResource) => { //browse all human resources categories
  
        if (categoryHumanResource.id == categoryResourceId) { //if the category exist
          //we don't set a message
          categoryExist = true;
        }
      })
      if (categoryExist == false) { //if the category doesn't exist
        var listCategoryOfResources = JSON.parse(document.getElementById("categoryOfHumanResourceJSON").value.replaceAll("3aZt3r", " "));
        listCategoryOfResources.forEach((categoryOfResource) => { //browse all category of resource
          if (categoryOfResource.idcategory == categoryResourceId) { //if we find the good category
            //set the category name
            categoryName = categoryOfResource.categoryname
          }
        })
      }
    }
  
    else { //if the resource is material
      scheduledActivity._def.extendedProps.categoryMaterialResource.forEach((categoryMaterialResource) => { //browse all material resources categories
        if (categoryMaterialResource.id == categoryResourceId) { //if the category exist
          //we don't set a message
          categoryExist = true;
        }
      })
      if (categoryExist == false) { //if the category doesn't exist
        var listCategoryOfResources = JSON.parse(document.getElementById("categoryOfMaterialResourceJSON").value.replaceAll("3aZt3r", " "));
        listCategoryOfResources.forEach((categoryOfResource) => { //browse all category of resource
          if (categoryOfResource.idcategory == categoryResourceId) { //if we find the good category
            //set the category name
            categoryName = categoryOfResource.categoryname
          }
        })
      }
    }
  
    if (categoryExist == false) { //if the category doesn't exist
      //we set the error message
      message = scheduledActivity.title + " n'a pas besoin de " + categoryName + ".";
    }
    return message;
  }
  
  /**
   * @brief This function return an error message if the ressource is unavailable during the scheduled activity, return "" if we have no problem
   * @param {*} scheduledActivity scheduled activity verified
   * @param {*} resourceId resource identifier verified
   * @returns the error message
   */
  function getMessageUnavailability(scheduledActivity, resourceId) {
    var messages = [];
  
    calendar.getEvents().forEach((unavailability) => { //browse all events
      if (unavailability._def.extendedProps.type == "unavailability") { //if events is an unavailability
        if (unavailability._def.publicId != scheduledActivity._def.publicId) {
          unavailability._def.resourceIds.forEach((compareResourceId) => { //browse all resource related to the unavailability
            if (compareResourceId != "h-default" && compareResourceId != "m-default") {
              if (compareResourceId == resourceId) {
                //if the resource is unavailable at the same time as the scheduled activity
                if ((scheduledActivity.start > unavailability.start && scheduledActivity.start < unavailability.end) || (scheduledActivity.end > unavailability.start && scheduledActivity.end < unavailability.end) || (scheduledActivity.start <= unavailability.start && scheduledActivity.end >= unavailability.end)) {
                  var listResources; //set the list resources
                  if (resourceId.substring(0, 5) == "human") {
                    listResources = JSON.parse(document.getElementById("human").value.replaceAll("3aZt3r", " "));
                  }
                  else {
                    listResources = JSON.parse(document.getElementById("material").value.replaceAll("3aZt3r", " "));
                  }
                  listResources.forEach((resource) => { //browse the list resources
                    if (resource.id == compareResourceId) {
                      //set an error message with the resource name
                      messages.push(resource.title + " est indisponible sur ce créneau. ");
                    }
                  })
                }
              }
            }
          })
        }
      }
    })
    return messages;
  }
  
  /**
   * @brief This function return an error message if the ressource is already scheduled during the scheduled activity, return "" if we have no problem
   * @param {*} scheduledActivity scheduled activity verified
   * @param {*} resourceId resource identifier verified
   * @returns the error message
   */
  function getMessageAlreadyExist(scheduledActivity, resourceId) {
    var messages = [];
  
    calendar.getEvents().forEach((compareScheduledActivity) => { //browse all events
      if(compareScheduledActivity._def.publicId!="now") { //if events is a scheduled activity
        if (compareScheduledActivity._def.extendedProps.type != "unavailability") { //if event is not an unavailability
          if (compareScheduledActivity._def.publicId != scheduledActivity._def.publicId) {
            compareScheduledActivity._def.resourceIds.forEach((compareResourceId) => { //browse all resource related to the scheduled activity compared
              if (compareResourceId != "h-default" && compareResourceId != "m-default") {
                if (compareResourceId == resourceId) {
                  //if the resource is already scheduled at the same time as the scheduled activity
                  if ((scheduledActivity.start > compareScheduledActivity.start && scheduledActivity.start < compareScheduledActivity.end) || (scheduledActivity.end > compareScheduledActivity.start && scheduledActivity.end < compareScheduledActivity.end) || (scheduledActivity.start <= compareScheduledActivity.start && scheduledActivity.end >= compareScheduledActivity.end)) {
                    compareScheduledActivity._def.extendedProps.humanResources.forEach((humanResource) => { //browse the list human resources
                      if (humanResource.id == compareResourceId) { //if the resource is a human resource
                        //set an error message
                        messages.push(humanResource.title + " est déjà programé sur " + compareScheduledActivity.title + ". ");
                      }
                    })
                    compareScheduledActivity._def.extendedProps.materialResources.forEach((materialResource) => { //browse the list material resources
                      if (materialResource.id == compareResourceId) { //if the resource is a material resource
                        //set an error message
                        messages.push(materialResource.title + " est déjà programé sur " + compareScheduledActivity.title + ".");
                      }
                    })
                  }
                }
              }
            })
          }
        }
      }
    })
  
    return messages;
  }
  
  /**
   * @brief This function return an error message if the ressource is not in working hours during the scheduled activity, return "" if we have no problem
   * @param {*} scheduledActivity scheduled activity verified
   * @param {*} humanResourceId resource identifier verified
   * @returns the error message
   */
  function getMessageWorkingHours(scheduledActivity, humanResourceId) {
    var message = [];
  
    var humanResources = JSON.parse(document.getElementById("human").value.replaceAll("3aZt3r", " ")); //recover all human resources
    humanResources.forEach((resource) => { //browse all human resources
      if (resource.id == humanResourceId) {
        workingHoursStart = new Date(currentDateStr.split("T")[0] + " " + resource.workingHours[0].startTime + ":00")
        workingHoursEnd = new Date(currentDateStr.split("T")[0] + " " + resource.workingHours[0].endTime + ":00")
        //if the human resource is not in working hours
        if (!(workingHoursStart <= new Date(scheduledActivity.start.getTime() - 2 * 60 * 60 * 1000) &&
          new Date(scheduledActivity.end.getTime() - 2 * 60 * 60 * 1000) <= workingHoursEnd)) {
          //set an error message
          message.push(resource.title + " n'est pas en horaire de travail sur ce créneau, il risque d'y avoir un conflit.");
        }
      }
    })
  
    return message;
  }
  
  /**
   * Called when button 'erreurs' is clicked, display or hide the lateral-panel-bloc and call the function to update informations. 
   */
  function displayPanelErrorMessages() {
    var lateralPannelBloc = document.querySelectorAll('#' + 'lateral-panel-bloc');
    var lateralPannel = document.querySelectorAll('#' + 'lateral-panel');
    var lateralPannelInput = document.getElementById('lateral-panel-input').checked;
    if (lateralPannelInput == true) {                   //Test the value of the checkbox
      lateralPannelBloc[0].style.display = 'block';  //display the panel
      lateralPannel[0].style.width = '30em';
    }
    else {
      lateralPannelBloc[0].style.display = ''; //hide the pannel
      lateralPannel[0].style.width = '';
    }
  
  }
  
  /**
   * Update the Panel of List error by removing all notifications and re-creating it with the new informations. 
   */
  function updatePanelErrorMessages() {
    var nodesNotification = document.getElementById('lateral-panel-bloc').childNodes;                             //Get the div in lateral-panel-bloc
    while (nodesNotification.length != 3) {                                                                         //the 3 first div are not notifications
      document.getElementById('lateral-panel-bloc').removeChild(nodesNotification[nodesNotification.length - 1]);  //Removing div 
    }
    var repertoryErrors = repertoryListErrors();                   //Get the repertory of errors 
    if (repertoryErrors.count != 0) {
      updateColorErrorButton(true);                                     //Updating the color of the button "erreurs"
      //add div for unscheduled appointment
      if (listErrorMessages.messageUnscheduledAppointment.length != 0) {
        var div = document.createElement('div');
        div.setAttribute('class', 'alert alert-warning');
        div.setAttribute('role', 'alert');
        div.setAttribute('id', 'notification' + 'unplanned');
        div.setAttribute('style', 'display: flex; flex-direction : column; cursor: pointer;');
        var divRow = document.createElement('divRow');
        divRow.setAttribute('style', 'display: flex; flex-direction : row; position:relative');
        div.append(divRow);
        var img = document.createElement("img");
        img.src = "/img/exclamation-triangle-fill.svg";
        img.style.height = "32px";
        var text = document.createElement('h3');
        text.innerHTML = 'Rendez-vous non planifiés';
  
        var expandButton = document.createElement('img');
        expandButton.setAttribute('src', '/img/chevron_down.svg');
        expandButton.setAttribute('id', 'expandButton');
        expandButton.setAttribute('style', 'background:none;height:50%;position:absolute;right:0%; display:none ;cursor: pointer;');
        expandButton.setAttribute('onclick', "reduceNotification(" + div.id + ")");
  
        var reduceButton = document.createElement('img');
        reduceButton.setAttribute('src', '/img/chevron_up.svg');
        reduceButton.setAttribute('id', 'reduceButton');
        reduceButton.setAttribute('style', 'background:none;height:50%;position:absolute;right:0%;cursor: pointer;');
        reduceButton.setAttribute('onclick', "reduceNotification(" + div.id + ")");
        divRow.append(img, text, reduceButton, expandButton);
  
        listErrorMessages.messageUnscheduledAppointment.forEach((oneMessageUnscheduledAppointment) => {
          var divColumn = document.createElement('divColumn');
          div.append(divColumn);
          var messageUnscheduledAppointment = document.createElement('earliestAppointmentDate').innerHTML = '-' + oneMessageUnscheduledAppointment;
          divColumn.append(messageUnscheduledAppointment);
        })
        document.getElementById('lateral-panel-bloc').append(div);
        //reduceButton
  
  
      }
  
      for (let i = 0; i < listErrorMessages.listScheduledAppointment.length; i++) {                                       //All Appointments of the day
        if (repertoryErrors.repertory.includes(i)) {                      //if the Appointment[i] has an error we have to display it
          var indexAppointment = repertoryErrors.repertory.indexOf(i);    //usefull to display activities 
          var div = document.createElement('div');                      //Creating the div for the Appointment
          div.setAttribute('class', 'alert alert-warning');
          div.setAttribute('role', 'alert');
          div.setAttribute('id', 'appointment' + listErrorMessages.listScheduledAppointment[i].appointmentId);
  
          div.setAttribute('style', 'display: flex; flex-direction : column;');
          var divRow = document.createElement('divRow');
          divRow.setAttribute('style', 'display: flex; flex-direction : row; position : relative; justify-content:space-between;');
          div.append(divRow);
          var img = document.createElement("img");
          img.src = "/img/exclamation-triangle-fill.svg";
          img.style.height = "32px";
          img.setAttribute('onclick', 'highlightAppointmentOnClick(this)');
          img.setAttribute('style', ' cursor: pointer;');
          var text = document.createElement('h3');
  
          //A implenter uniquement sur h3
          div.setAttribute('onmouseover', 'highlightAppointmentOnMouseOver(this)');
          div.setAttribute('onmouseout', 'highlightAppointmentOnMouseOut(this)');
          text.setAttribute('onclick', 'highlightAppointmentOnClick(this)');
          text.setAttribute('style', ' cursor: pointer;');
          text.innerHTML = listErrorMessages.listScheduledAppointment[i].patientName + ' / ' + listErrorMessages.listScheduledAppointment[i].pathwayName;
  
          //button for hidding the information
          var reduceButtonAppointment = document.createElement('img');
          reduceButtonAppointment.setAttribute('src', '/img/chevron_up.svg');
          reduceButtonAppointment.setAttribute('id', 'reduceButton' + div.id)
          reduceButtonAppointment.setAttribute('style', 'background:none;height:24px;margin-left:5%;cursor: pointer;');
          reduceButtonAppointment.setAttribute('onclick', "reduceNotification(" + div.id + ")");
  
          //button for displaying the information
          var expandButtonAppointment = document.createElement('img');
          expandButtonAppointment.setAttribute('src', '/img/chevron_down.svg');
          expandButtonAppointment.setAttribute('id', 'expandButton' + div.id)
          expandButtonAppointment.setAttribute('style', 'background:none;height:24px;display:none;margin-left:5%;cursor: pointer;');
          expandButtonAppointment.setAttribute('onclick', "reduceNotification(" + div.id + ")");
  
          divRow.append(img, text, reduceButtonAppointment, expandButtonAppointment);
  
          //messageEarliestAppointmentTime
          if (listErrorMessages.listScheduledAppointment[i].messageEarliestAppointmentTime != []) {
            listErrorMessages.listScheduledAppointment[i].messageEarliestAppointmentTime.forEach((messageEarliest) => {
              var divColumn = document.createElement('divColumn');
              div.append(divColumn);
              var messageEarliestAppointmentTime = document.createElement('earliestAppointmentDate').innerHTML = '-' + messageEarliest;
              divColumn.append(messageEarliestAppointmentTime);
              divColumn.setAttribute('onclick', 'highlightAppointmentOnClick(this)');
              divColumn.setAttribute('style', ' cursor: pointer;');
              var space = document.createElement('space');
              space.innerHTML = '</br>';
              div.append(space);
            })
          }
  
          //messageLatestAppointmentTime
          if (listErrorMessages.listScheduledAppointment[i].messageLatestAppointmentTime != []) {
            listErrorMessages.listScheduledAppointment[i].messageLatestAppointmentTime.forEach((messageLatest) => {
              var divColumn = document.createElement('divColumn');
              div.append(divColumn);
              var messageLatestAppointmentTime = document.createElement('messageLatestAppointmentTime').innerHTML = '-' + messageLatest;
              divColumn.append(messageLatestAppointmentTime);
              divColumn.setAttribute('onclick', 'highlightAppointmentOnClick(this)');
              divColumn.setAttribute('style', ' cursor: pointer;');
              var space = document.createElement('space');
              space.innerHTML = '</br>';
              div.append(space);
            })
          }
  
          //for each ScheduledActivity in Appointment 
          for (let listeSAiterator = 0; listeSAiterator < listErrorMessages.listScheduledAppointment[i].listScheduledActivity.length; listeSAiterator++) {
            if (repertoryErrors.repertoryAppointmentSAError[indexAppointment].repertorySA.includes(listeSAiterator)) {          //Testing if there are errors on this Activity
              var divColumn = document.createElement('divColumn');
              divColumn.setAttribute('style', 'font-weight: bolder;')
              divColumn.setAttribute('onclick', 'highlightAppointmentOnClick(this)');
              divColumn.setAttribute('style', ' cursor: pointer;');
              div.append(divColumn);
              var nameSA = listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].scheduledActivityName + ' : ';        //Display Activity Name 
              divColumn.append(nameSA);
  
              //messageNotFullyScheduled
              if (listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].messageNotFullyScheduled != '') {
                var divColumn = document.createElement('divColumn');
                div.append(divColumn);
                var messageNotFullyScheduled = document.createElement('messageNotFullyScheduled').innerHTML = '-' + listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].messageNotFullyScheduled;
                divColumn.setAttribute('onclick', 'highlightAppointmentOnClick(this)');
                divColumn.setAttribute('style', ' cursor: pointer;');
                divColumn.append(messageNotFullyScheduled);
              }
  
              //messageAppointmentActivityAlreadyScheduled
              if (listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].messageAppointmentActivityAlreadyScheduled != []) {
                listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].messageAppointmentActivityAlreadyScheduled.forEach((oneMessageAppointmentActivityAlreadyScheduled) => {
                  var divColumn = document.createElement('divColumn');
                  div.append(divColumn);
                  var messageAppointmentActivityAlreadyScheduled = document.createElement('messageAppointmentActivityAlreadyScheduled').innerHTML = '-' + oneMessageAppointmentActivityAlreadyScheduled;
                  divColumn.setAttribute('onclick', 'highlightAppointmentOnClick(this)');
                  divColumn.setAttribute('style', ' cursor: pointer;');
                  divColumn.append(messageAppointmentActivityAlreadyScheduled);
                })
              }
  
              //messageDelay
              if (listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].messageDelay != []) {
                listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].messageDelay.forEach((oneMessageDelay) => {
                  var divColumn = document.createElement('divColumn');
                  div.append(divColumn);
                  var messageDelay = document.createElement('messageDelay').innerHTML = '-' + oneMessageDelay;
                  divColumn.setAttribute('onclick', 'highlightAppointmentOnClick(this)');
                  divColumn.setAttribute('style', ' cursor: pointer;');
                  divColumn.append(messageDelay);
                })
              }
  
  
            }
  
            //foreach CategoryHumanResources in ScheduledActivity
            for (let listCategoryHumanResourcesItorator = 0; listCategoryHumanResourcesItorator < listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listCategoryHumanResources.length; listCategoryHumanResourcesItorator++) {
  
              //messageCategoryQuantity
              if (listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listCategoryHumanResources[listCategoryHumanResourcesItorator].messageCategoryQuantity != '') {
                var divColumn = document.createElement('divColumn');
                div.append(divColumn);
                var messageCategoryQuantity = document.createElement('messageCategoryQuantity').innerHTML = '-' + listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listCategoryHumanResources[listCategoryHumanResourcesItorator].messageCategoryQuantity;
                divColumn.setAttribute('onclick', 'highlightAppointmentOnClick(this)');
                divColumn.setAttribute('style', ' cursor: pointer;');
                divColumn.append(messageCategoryQuantity);
              }
  
              //messageWrongCategory
              if (listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listCategoryHumanResources[listCategoryHumanResourcesItorator].messageWrongCategory != '') {
                var divColumn = document.createElement('divColumn');
                div.append(divColumn);
                var messageWrongCategory = document.createElement('messageWrongCategory').innerHTML = '-' + listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listCategoryHumanResources[listCategoryHumanResourcesItorator].messageWrongCategory;
                divColumn.setAttribute('onclick', 'highlightAppointmentOnClick(this)');
                divColumn.setAttribute('style', ' cursor: pointer;');
                divColumn.append(messageWrongCategory);
              }
            }
  
            //foreach HumanResources
            for (let listHumanResourcesIterator = 0; listHumanResourcesIterator < listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listHumanResources.length; listHumanResourcesIterator++) {
  
              //messageWorkingHours
              if (listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listHumanResources[listHumanResourcesIterator].messageWorkingHours != []) {
                listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listHumanResources[listHumanResourcesIterator].messageWorkingHours.forEach((oneMessageWorkingHours) => {
                  var divColumn = document.createElement('divColumn');
                  div.append(divColumn);
                  var messageWorkingHours = document.createElement('messageWorkingHours').innerHTML = '-' + oneMessageWorkingHours;
                  divColumn.setAttribute('onclick', 'highlightAppointmentOnClick(this)');
                  divColumn.setAttribute('style', ' cursor: pointer;');
                  divColumn.append(messageWorkingHours);
                })
              }
  
  
              //messageUnavailability
              if (listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listHumanResources[listHumanResourcesIterator].messageUnavailability != []) {
                listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listHumanResources[listHumanResourcesIterator].messageUnavailability.forEach((oneMessageUnavailability) => {
                  var divColumn = document.createElement('divColumn');
                  div.append(divColumn);
                  var messageUnavailability = document.createElement('messageUnavailability').innerHTML = '-' + oneMessageUnavailability;
                  divColumn.setAttribute('onclick', 'highlightAppointmentOnClick(this)');
                  divColumn.setAttribute('style', ' cursor: pointer;');
                  divColumn.append(messageUnavailability);
                })
              }
  
              //messageAlreadyScheduled
              if (listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listHumanResources[listHumanResourcesIterator].messageAlreadyScheduled != []) {
                listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listHumanResources[listHumanResourcesIterator].messageAlreadyScheduled.forEach((oneMessageAlreadyScheduled) => {
                  var divColumn = document.createElement('divColumn');
                  div.append(divColumn);
                  var messageAlreadyScheduled = document.createElement('messageAlreadyScheduled').innerHTML = '-' + oneMessageAlreadyScheduled;
                  divColumn.setAttribute('onclick', 'highlightAppointmentOnClick(this)');
                  divColumn.setAttribute('style', ' cursor: pointer;');
                  divColumn.append(messageAlreadyScheduled);
                })
              }
            }
  
            //foreach MaterialResourcesCategory in ScheduledActivity
            for (let listCategoryMaterialResourcesItorator = 0; listCategoryMaterialResourcesItorator < listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listCategoryMaterialResources.length; listCategoryMaterialResourcesItorator++) {
  
              //messageCategoryQuantity
              if (listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listCategoryMaterialResources[listCategoryMaterialResourcesItorator].messageCategoryQuantity != '') {
                var divColumn = document.createElement('divColumn');
                div.append(divColumn);
                var messageCategoryQuantity = document.createElement('messageCategoryQuantity').innerHTML = '-' + listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listCategoryMaterialResources[listCategoryMaterialResourcesItorator].messageCategoryQuantity;
                divColumn.setAttribute('onclick', 'highlightAppointmentOnClick(this)');
                divColumn.setAttribute('style', ' cursor: pointer;');
                divColumn.append(messageCategoryQuantity);
              }
  
              //messageWrongCategory
              if (listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listCategoryMaterialResources[listCategoryMaterialResourcesItorator].messageWrongCategory != '') {
                var divColumn = document.createElement('divColumn');
                div.append(divColumn);
                var messageWrongCategory = document.createElement('messageWrongCategory').innerHTML = '-' + listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listCategoryMaterialResources[listCategoryMaterialResourcesItorator].messageWrongCategory;
                divColumn.setAttribute('onclick', 'highlightAppointmentOnClick(this)');
                divColumn.setAttribute('style', ' cursor: pointer;');
                divColumn.append(messageWrongCategory);
              }
            }
  
            //foreach MaterialResources 
            for (let listMaterialResourcesIterator = 0; listMaterialResourcesIterator < listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listMaterialResources.length; listMaterialResourcesIterator++) {
  
              //messageUnavailability
              if (listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listMaterialResources[listMaterialResourcesIterator].messageUnavailability != []) {
                listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listMaterialResources[listMaterialResourcesIterator].messageUnavailability.forEach((oneMessageUnavailability) => {
                  var divColumn = document.createElement('divColumn');
                  div.append(divColumn);
                  var messageUnavailability = document.createElement('messageUnavailability').innerHTML = '-' + oneMessageUnavailability;
                  divColumn.setAttribute('onclick', 'highlightAppointmentOnClick(this)');
                  divColumn.setAttribute('style', ' cursor: pointer;');
                  divColumn.append(messageUnavailability);
                })
              }
  
              //messageAlreadyScheduled
              if (listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listMaterialResources[listMaterialResourcesIterator].messageAlreadyScheduled != []) {
                listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listMaterialResources[listMaterialResourcesIterator].messageAlreadyScheduled.forEach((oneMessageAlreadyScheduled) => {
                  var divColumn = document.createElement('divColumn');
                  div.append(divColumn);
                  var messageAlreadyScheduled = document.createElement('messageAlreadyScheduled').innerHTML = '-' + oneMessageAlreadyScheduled;
                  divColumn.setAttribute('onclick', 'highlightAppointmentOnClick(this)');
                  divColumn.setAttribute('style', ' cursor: pointer;');
                  divColumn.append(messageAlreadyScheduled);
                })
              }
            }
  
            if (repertoryErrors.repertoryAppointmentSAError[indexAppointment].repertorySA.indexOf(listeSAiterator) != repertoryErrors.repertoryAppointmentSAError[indexAppointment].repertorySA.length - 1) {
              var space = document.createElement('space'); //skip to line if there is an error in another activity in this appointment
              space.innerHTML = '</br>';
              div.append(space);
            }
          }
          document.getElementById('lateral-panel-bloc').append(div); //Append all the messages into the lateral-panel-bloc
        }
      }
    }
    else {     //No errors
      var div = document.createElement('div');
      div.setAttribute('class', 'alert alert-success');
      div.setAttribute('role', 'alert');
      div.setAttribute('style', 'text-align: center');
      var message = document.createElement('message').innerHTML = "Aucune erreur détectée.";  //Display 'no error' message
      div.append(message);
      document.getElementById('lateral-panel-bloc').append(div);
  
      updateColorErrorButton(false);  //Update color of the button                                                    
    }
  }
  
  /**
   * This function count the number of Appointments with errors and get the Activities repertory of errors, used only in updatePanelErrorMessages()
   * @returns count is the number of Apppointments with errors
   * @returns repertory is the repertory of Appointments with errors, usefull to display Appointments 
   * @returns repertoryAppointmentSAError is the repertory of ScheduledActivites with error for an appointment, usefull to display ScheduledActivities. 
   */
  function repertoryListErrors() {
    var countAppointmentError = 0;
    var repertoryAppointmentError = [];
    var repertoryAppointmentSAError = [];
    if (listErrorMessages.messageUnscheduledAppointment != []) {
      listErrorMessages.messageUnscheduledAppointment.forEach((message) => {
        countAppointmentError++;
      })
    }
    for (let i = 0; i < listErrorMessages.listScheduledAppointment.length; i++) {
      var errorInappointment = false;
  
      //messageEarliestAppointmentTime
      if (listErrorMessages.listScheduledAppointment[i].messageEarliestAppointmentTime != '') {
        errorInappointment = true;
      }
  
      //messageLatestAppointmentTime
      if (listErrorMessages.listScheduledAppointment[i].messageLatestAppointmentTime != '') {
        errorInappointment = true;
      }
  
      //foreach ScheduledActivity in appointment
      var repertorySAError = [];
      var repertorySAErrorId = [];
      for (let listeSAiterator = 0; listeSAiterator < listErrorMessages.listScheduledAppointment[i].listScheduledActivity.length; listeSAiterator++) {
        var errorInScheduledActivity = false;
  
        //messageDelay
        if (listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].messageDelay != '') {
          errorInappointment = true;
          errorInScheduledActivity = true;
        }
  
        if (listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].messageNotFullyScheduled != '') {
          errorInappointment = true;
          errorInScheduledActivity = true;
        }
  
        //foreach CategoryhumanResources in ScheduledActivity
        for (let listCategoryHumanResourcesItorator = 0; listCategoryHumanResourcesItorator < listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listCategoryHumanResources.length; listCategoryHumanResourcesItorator++) {
  
          //messageCategoryQuantity
          if (listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listCategoryHumanResources[listCategoryHumanResourcesItorator].messageCategoryQuantity != '') {
            errorInappointment = true;
            errorInScheduledActivity = true;
          }
  
          //messageWrongCategory
          if (listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listCategoryHumanResources[listCategoryHumanResourcesItorator].messageWrongCategory != '') {
            errorInappointment = true;
            errorInScheduledActivity = true;
          }
        }
  
        //foreach HUmanResources 
        for (let listHumanResourcesIterator = 0; listHumanResourcesIterator < listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listHumanResources.length; listHumanResourcesIterator++) {
          if (listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listHumanResources[listHumanResourcesIterator].messageWorkingHours != '') {
            errorInappointment = true;
            errorInScheduledActivity = true;
          }
  
          //messageUnavailability
          if (listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listHumanResources[listHumanResourcesIterator].messageUnavailability != '') {
            errorInappointment = true;
            errorInScheduledActivity = true;
          }
  
          //messageAlreadyScheduled
          if (listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listHumanResources[listHumanResourcesIterator].messageAlreadyScheduled != '') {
            errorInappointment = true;
            errorInScheduledActivity = true;
          }
        }
  
        //foreach CategoryMaterialResources in ScheduledActivity
        for (let listCategoryMaterialResourcesItorator = 0; listCategoryMaterialResourcesItorator < listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listCategoryMaterialResources.length; listCategoryMaterialResourcesItorator++) {
  
          //messageCategoryQuantity
          if (listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listCategoryMaterialResources[listCategoryMaterialResourcesItorator].messageCategoryQuantity != '') {
            errorInappointment = true;
            errorInScheduledActivity = true;
          }
  
          //messageWrongCategory
          if (listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listCategoryMaterialResources[listCategoryMaterialResourcesItorator].messageWrongCategory != '') {
            errorInappointment = true;
            errorInScheduledActivity = true;
          }
        }
  
        //foreach MaterialResource
        for (let listMaterialResourcesIterator = 0; listMaterialResourcesIterator < listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listMaterialResources.length; listMaterialResourcesIterator++) {
  
          //messageUnavailability
          if (listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listMaterialResources[listMaterialResourcesIterator].messageUnavailability != '') {
            errorInappointment = true;
            errorInScheduledActivity = true;
          }
  
          //messageAlreadyScheduled
          if (listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].listMaterialResources[listMaterialResourcesIterator].messageAlreadyScheduled != '') {
            errorInappointment = true;
            errorInScheduledActivity = true;
          }
        }
  
        if (errorInScheduledActivity == true) {         //if true there is an error in the ScheduledActivity
          repertorySAError.push(listeSAiterator);
          repertorySAErrorId.push(listErrorMessages.listScheduledAppointment[i].listScheduledActivity[listeSAiterator].scheduledActivityId)
        }
      }
      if (errorInappointment == true) {               //if true there is an error in the appointment
        countAppointmentError++;
        repertoryAppointmentError.push(i);
        repertoryAppointmentSAError.push({
          appointment: i,
          appointmentId: listErrorMessages.listScheduledAppointment[i].appointmentId,
          repertorySA: repertorySAError,
          repertorySAErrorId: repertorySAErrorId
        });
  
      }
    }
    return { count: countAppointmentError, repertory: repertoryAppointmentError, repertoryAppointmentSAError: repertoryAppointmentSAError };
  }
  
  /**
   * This function changes dynamically the color and color of the text for the label 'erreurs'.  
   * @param state boolean, give the information to know what is the apropriate color for the situation
   */
  function updateColorErrorButton(state) {
    var button = document.getElementById('lateral-panel-label');  //Get the label
    switch (state) {
      case true:
        button.setAttribute('style', 'background : indianred; color :white');  //one or more error(s)
        break;
      case false:
        button.setAttribute('style', 'background : white; color : indianred')  //zero error
    }
  
  }
  
  /**
   * This function reduce the notification in the errorList messages. 
   * @param {*} childs all the child in the parent division of the menu in list error page. 
   */
  function reduceNotification(childs) {
    if (childs.id == 'notificationunplanned') {
      if (childs.childNodes[1].style.display == '') {
        for (let i = 1; i < childs.childNodes.length; i++) {
          childs.childNodes[i].style.display = 'none';  //reducing what is displayed
        }
        document.getElementById('expandButton').style.display = '' //expanding what is displayed
        document.getElementById('reduceButton').style.display = 'none'
      }
      else {
        for (let i = 1; i < childs.childNodes.length; i++) {
          childs.childNodes[i].style.display = '';
        }
        document.getElementById('expandButton').style.display = 'none'
        document.getElementById('reduceButton').style.display = ''
      }
    }
    else {
  
      if (childs.childNodes[1].style.display == '') {
        for (let i = 1; i < childs.childNodes.length; i++) {
          childs.childNodes[i].style.display = 'none';
        }
        document.getElementById('expandButton' + childs.id).style.display = ''
        document.getElementById('reduceButton' + childs.id).style.display = 'none'
      }
      else {
        for (let i = 1; i < childs.childNodes.length; i++) {
          childs.childNodes[i].style.display = '';
        }
        document.getElementById('expandButton' + childs.id).style.display = 'none'
        document.getElementById('reduceButton' + childs.id).style.display = ''
      }
  
    }
  
  
  }
  
  /**
   * @brief This function update event color when the mouse over an error message
   * @param {*} event 
   */
   function highlightAppointmentOnMouseOver(event) {
    var appointmentId = event.id.substring(11);
    calendar.getEvents().forEach((scheduledActivity) => {
      if (scheduledActivity._def.extendedProps.appointment == appointmentId) {
        if (scheduledActivity._def.ui.borderColor != "#ff0000" && scheduledActivity._def.ui.borderColor != "#006400") {
          scheduledActivity._def.ui.textColor = "#212529";
          if (RessourcesAllocated(scheduledActivity) == "#841919") {
            scheduledActivity._def.ui.backgroundColor = "#ff6a78";
            scheduledActivity._def.ui.borderColor = "#ff0001";
          }
          else {
            scheduledActivity._def.ui.backgroundColor = "#81f989";
            scheduledActivity._def.ui.borderColor = "#006401";
          }
        }
        scheduledActivity.setEnd(scheduledActivity.end);
      }
    })
  }
  
  /**
   * @brief This function get back the color of an event when the mouse leave an error message
   * @param {*} event 
   */
  function highlightAppointmentOnMouseOut(event) {
  
    var appointmentId = event.id.substring(11);
    calendar.getEvents().forEach((scheduledActivity) => {
      if (scheduledActivity._def.extendedProps.appointment == appointmentId) {
        if (scheduledActivity._def.ui.borderColor == "#ff0001" || scheduledActivity._def.ui.borderColor == "#006401") {
          scheduledActivity._def.ui.textColor = "#fff";
          scheduledActivity._def.ui.borderColor = RessourcesAllocated(scheduledActivity);
          scheduledActivity._def.ui.backgroundColor = RessourcesAllocated(scheduledActivity);
        }
        scheduledActivity.setEnd(scheduledActivity.end);
      }
    })
  }
  
  /**
   * @brief This function update event color when we click on an error message
   * @param {*} event 
   */
  function highlightAppointmentOnClick(event) {
    var appointmentId;
    if(event.parentElement.id.substring(0, 11) == "appointment"){
      appointmentId = event.parentElement.id.substring(11);
    }
    else if(event.parentElement.parentElement.id.substring(0, 11) == "appointment"){
      appointmentId = event.parentElement.parentElement.id.substring(11);
    }
  
    calendar.getEvents().forEach((scheduledActivity) => {
      if (scheduledActivity._def.extendedProps.appointment == appointmentId) {
        if (scheduledActivity._def.ui.borderColor == "#ff0000" || scheduledActivity._def.ui.borderColor == "#006400") {
          scheduledActivity._def.ui.textColor = "#fff";
          scheduledActivity._def.ui.borderColor = RessourcesAllocated(scheduledActivity);
          scheduledActivity._def.ui.backgroundColor = RessourcesAllocated(scheduledActivity);
        }
        else {
          scheduledActivity._def.ui.textColor = "#212529";
          if (RessourcesAllocated(scheduledActivity) == "#841919") {
            scheduledActivity._def.ui.borderColor = "#ff0000";
          }
          else {
            scheduledActivity._def.ui.borderColor = "#006400";
          }
        }
      }
      else {
        if (scheduledActivity._def.ui.borderColor == "#ff0000" || scheduledActivity._def.ui.borderColor == "#006400") {
          scheduledActivity._def.ui.textColor = "#fff";
          scheduledActivity._def.ui.borderColor = RessourcesAllocated(scheduledActivity);
          scheduledActivity._def.ui.backgroundColor = RessourcesAllocated(scheduledActivity);
        }
      }
      scheduledActivity.setEnd(scheduledActivity.end);
    })
  }