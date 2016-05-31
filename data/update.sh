#php generate.php

echo "act_id,issue_date,voting_date" > date.csv
jq -r ".[]|[.act_id,.issue_date,.voting_date]|@csv" print_doc_dates.log >> date.csv
