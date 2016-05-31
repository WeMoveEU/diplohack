#php generate.php

echo "act_id,issue_date,voting_date" > date.csv
jq -r ".[]|[.act_id,.issue_date,.voting_date]|@csv" print_doc_dates.log >> date.csv

#add the votes in the EP
#vi ep6.csv %s%(% (%g))
#q "select v.*,ep.nb_amendment,ep.ep_date from votes.csv v left join ep6.csv ep on docnrinterinst=doc_id" -d, -H -O

#the issue date (first date in the council)
#q "select v.*,d.issue_date from votes.csv v left join date.csv as d on act=act_id" -d, -H -O 

#q "select v.*,ep.nb_amendment,ep.ep_date,d.issue_date from votes.csv v left join ep6.csv ep on docnrinterinst=doc_id left join date.csv as d on act=act_id" -d, -H -O > complete.csv

q "select v.*,ep.nb_amendment,min(ep.ep_date) as ep_date,min(d.issue_date) as issue_date, count(*) as dup from votes.csv v left join ep6.csv ep on docnrinterinst=doc_id left join date.csv as d on act=act_id group by act" -d, -H -O > complete.csv

