##Events

###Project Events
|Name|Origin|Observer|Interest|
|---|---|---|---|
|project_drafted|Project Management|Project Management|To notify the senior project manager that he can assign a project manager to start the project|
|project_started|Project Management|Project Management|To start adding specialists|
|project_closed|Project Management|Invoicing|To start the invoicing process|

###Specialist Events
|Name|Origin|Observer|Interest|
|---|---|---|---|
|potential_specialist_put_on_list|Project Management|Prospecting|To create a new Prospect|
|prospect_registered|Procurement|Project Management|To know that there is a new specialist|
|prospect_received|Procurement|Procurement|To start the process of getting them to register via chasing up|
|specialist_approved|Project Management|Project Management|To know that there is a specialist to discuss with the client|
|specialist_discarded|Project Management|Project Management|??? May not really be needed ???|

###Client Events
|Name|Origin|Observer|Interest|
|---|---|---|---|
|client_service_suspended|Sales|Project Management|To put all their projects on hold|
|client_operations_resumed|Sales|Project Management|To reactivate all their projects|

###Package Events
|Name|Origin|Observer|Interest|
|---|---|---|---|
|package_purchased|Sales|Invoicing|To know that there is a package that can be used to attach Consultations to|
