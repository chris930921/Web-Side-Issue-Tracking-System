SELECT 
ticket_issue.id, 
ticket_issue.title, 
states.name as state, 
priorities.name as priority,
schedule_issue.occurency_date,
schedule_issue.expectation_date,
schedule_issue.finished_date

 FROM ajax_final_web.ticket_issue 
 LEFT JOIN schedule_issue ON schedule_issue.ticket_id = ticket_issue.id 
 LEFT JOIN states ON states.id = ticket_issue.state 
 LEFT JOIN priorities ON priorities.id = ticket_issue.priority

 WHERE 'a56ee8b4e97c16f615b024c7fd1a1a2717179d8' IN (SELECT token FROM ajax_final_web.token);