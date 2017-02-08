####Roles
|Term|Alternatives|Definition|Confirmed|
|---|---|---|---|
|Account||An invoicing model for a Client (Package or Pay-as-you-go). A client can have multiple packages plus (optionally) an agreed Pay-as-you-go model|N|
|Invoicing||The role of the Financial Department|N|

####Invoicing Models
|Term|Alternatives|Definition|Confirmed|
|---|---|---|
|Package|Account|A pre-purchased amount of consultation time with a number of hours and a validity duration|N|
|Pay-as-you-go||A payment model based on an agreed fixed hourly rate for Consultations with Invoicing per Project|N|

####Project
|Term|Alternatives|Meaning|Confirmed|
|---|---|---|---|
|Closed Project|Ended Project|When a project will not have any additional Consultations and can be processed for invoicing|N|
|Unassigned Project||A project which has not yet been assigned to a Package|N|
|Ended||A project reaches the point where invoicing for it can start|N|

####Consultation
|Term|Alternatives|Meaning|Confirmed|
|---|---|---|---|
|Duration||The amount of time (rounded to 15 minutes) that a consultation lasted|N|
|Billable||A consultation that belongs to a closed project and can be attached to a Package or invoiced using Pay as you go|Y|

####Packages
|Term|Alternatives|Definition|Confirmed|
|---|---|---|---|
|Assign [a closed Project to]||Determine that the consultation hours for that project should be deducted from this package|N|
|Nominal Hours||The number of hours a client has purchased|N|
|Available Hours||Nominal + what is transferred in|N|
|Remaining Hours|Available - whatever got used - whatever got transferred out|N|
|Reference||consist of name, which is generally just a name or a grade, like gold, or silver, but it is really just a reference, it can be anything, plus year and month the when the package started, and finally the length|N|
|Fixed Hourly Rate||The agreed cost of a 1 hour consultation for a given client|N|
|Purchase|Buy|When a Client pays for a new Package|N|
|Close Down||Changing the state of a package to prevent it being used further|
|Close Down Manually||Changing the state of a package to prevent it being used further|
|Ran Over||A package which has been allocated projects which exceed the pre-purchased amount of Consultation time|

####Package states
|Term|Alternatives|Definition|Confirmed|
|---|---|---|---|
|Active|Started|A package which has a start date in the past and is not yet Closed or Expired|Y|
|Inactive||A package which has a start date in the future|Y|
|Expired||A package which can no longer be used because the time it could run for has been exceeded|N|
|Closed||Changing the state of a package to prevent it being used further|
