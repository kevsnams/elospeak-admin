# Admin Panel Notes
All of the classrooms created on admin should be saved with PHT (UTC +8) in mind.
## Classrooms
The previous classroom constants were a pain in the ass to deal with. Instead, limiting it to these 3 constants removes the problem:

- `Classroom::STATUS_ACTIVE` (Default)

- `Classroom::STATUS_DONE`

- `Classrooms::STATUS_CANCELLED`



#### Setting Start/End DateTime of Classroom
The input accepts:

`start_date`

The date when the classroom should start

Format: It should in `Y-m-d` format [*Read More: PHP date*](https://www.php.net/date)

`start_time`

The time when the classroom should start

Format: It should be in 24-H format and leading zeros are optional for both H and M
`(?H)H:(?M)M`

Examples:

|  Input | Result  |
| ------------ | ------------ |
| 13:30  | 1:30 PM  |
|  5:00 | 5:00 AM  |
| 09:00 | 9:00 AM |


`duration`

The class duration in **minutes**
