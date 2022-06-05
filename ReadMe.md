# Supplier Product List Processor

#### Requirement: 

We have multiple different formats of files that need to be parsed and returned back as a Product object with all the headings as mapped properties. 

Developed using PHP Version => 7.4.3

#### How to use:

    fileParser --input=example_1.csv --output=combination_count.csv

Running the program will display each product as the input file is read.  If any rows contain
errors, the original input line will be displayed along with the error message.

##### Parameters:

All parameters need to be of the format

    --name=value

Parameters list:


| Name           | Value                                                         |
| -------------- | ------------------------------------------------------------- |
| input          | file name to read from                                        |
| output         | file name to write to                                         |
| reject         | file name to write any rejected lines to *                    |
| inputadaptor   | which adaptor to use (defaults to file extension) *           |
| outputadaptor  | which adaptor to use (defaults to file extension) *           |
 
 (Note: * - these options are not implemented yet).
 
 #### Supported file formats:
 
 ##### Input
 
 When running the program, it will select which format it is expecting according to the extension of the file.
 
 .csv files in the following format:
 
    "brand_name","model_name","condition_name","grade_name","gb_spec_name","colour_name","network_name"
    "AASTRA","6865I","Working","Grade B - Good Condition","Not Applicable","Black","Not Applicable"
 
 .json files in the following format:
 
    [
         {
            "brand_name": "ABOOK",
            "model_name": "1720Wx",
            "phones": [
                {
                    "condition_name": "Working",
                    "grade_name": "Grade B - Good Condition",
                    "gb_spec_name": "4GB",
                    "colour_name": "Black",
                    "network_name": "Not Applicable"
                }
            ]
        }
    ]
    

##### Output

CSV:

    make,model,colour,capacity,network,grade,condition,count
    AASTRA,6865I,Black,"Not Applicable","Not Applicable","Grade B - Good Condition",Working,3

#### Notes:

##### JSON format
This has been included as a proof of concept.  CSV is presented as 1 line in, 1 product
generated.  JSON and XML may include products presented in a hierarchical 
manner.  This particular JSON format introduces a new challenge, this is intended 
to show how reading the input source is used in a way that allows multiple records to be 
generated.  The example content shows brand and model as a top layer with potentially several
different grade, colour combinations underneath.

##### TODO tasks
There are various of these scattered amongst the code.  These are intended to indicate where
either minor assumptions have been made and perhaps need to be confirmed, or where further
development could improve the flexibility and modularity of the code.  Some may be suggestions
of how the code could be extended or highlight the limitations of the solution implemented
here.
