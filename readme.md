## File storage (Laravel)

### Using
#### API
* List files
```
GET /api/files
```
 
* Add a file to bucket
```
POST /api/files 

{"resource": "<path to file>"}
```

* File info
```
/api/files/{fileId}
```

#### Console
* List files
```
php artisan bucket:list [--full-strings]
```
Optional options: 
`--full-strings` – show full links and file names in table

* Add a file to bucket
```
php artisan bucket:add [--resource=<path to file>]
``` 
Required options: `--resource` – Resource file link 

#### Web

![Web](https://i.postimg.cc/h4dhSx5k/Screen-Shot-2019-04-04-at-4-48-29-AM.png)
