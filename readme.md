## Partner Performance

This is a project built to pull data from DHIS and display it on a dashboard. It is built on laravel 5.6. For more on laravel, please read the docs. [Click here](https://partnerperformance.org) to see the system in action.

The page loads initially and the charts are loaded using ajax requests. The filtering options are stored in the session. Redis works best as a session store. Laravel provides a driver for Redis. The requests to set the filtering options are post requests and the requests to load the charts are get requests. To get a chance to see it in action, view the source on the live system.

The database structure is flat. We store aggregated data at the facility level which allows us to aggregate it upwards on demand. Data is stored monthly with a table called periods storing data on the month such as which particular month it's in and which quarter and financial year it is in. The basic structure of a data table is:
- **id** autoincrementing id
- **period_id** A foreign key matching which period (exact month) it is in.
- **facility** A foreign key matching with the facility of the row.
- **data_columns** (*Multiple columns*) Integer fields containing totals for the period for that the particular facility. Each data column holds a specific piece of data e.g. total number of patients.

### Monitor requests using Telescope.

Hit this end point in you browser.

http://yourhost/telescope/requests

### Etl Process pull
ETL means Extraction, Transformation, Load.  You can read more about it here. https://www.ibm.com/cloud/learn/etl.

## How to create an etl instance.
# There are 3 steps to this.
    Make your models and migrations with controllers if necessary.
        (#). This involves model for both etl and remote.
            ```properties
            php artisan make:model view_facilitys -mrc 
            #this will create a model with migrations, and controllers.
            
            php artisan make:model view_facilitys_etl 
            #always place models etl models in path etl/models ... remember to change namespace for it to work.

            php artisan make:command view_facilitys
            
            ```  


