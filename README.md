# Flora-addon

[LMS]: https://github.com/Xinyu-Li/flora-lms
[hyp-installation-docs]: https://memex.readthedocs.io/en/stable/index.html
[hyp-framework]: https://github.com/ssin122/flora-addon/blob/main/h.pdf
[hyp-inst]:https://github.com/ssin122/flora-addon/blob/main/hyp_inst.md

This repo is part of the FLoRA Project and needs to be installed in conjunction with the [LMS] repository. The features implemented in this repo extends the  Moodle LMS to provide:

* An Annotation tool  ([hyp-installation-docs] or [hyp-framework] or [hyp-inst])
* An Essay writing interface
* A Timer Tool
* A Planner Tool
* Scaffolds (generalised & personalised).
* Log Viewer Interface
* DB configurations settings

Since this repo is closely built around the Moodle LMS, the steps to correctly integrate this repo is discussed on the [LMS] page.

## Quick Customisation Tips:
To customise the various instrumentation tools, you can locate their respective code in the following folders:
* Essay writing interface : flora-addon/flora/Essay/
* Timer Tool : flora-addon/flora/Timer/
* Planner Tool : flora-addon/flora/Planner/
* Scaffolds : flora-addon/flora/scaffolds/
  -  To change timing: Line 106 in flora-addon/flora/scaffolds/scf_client.js
* Scaffolds (Improved) : flora-addon/flora/scaffolds_imp/
  -  To change timing: Line 100 in flora-addon/flora/scaffolds_imp/scf_client.js
* Log Viewer Interface : flora-addon/logs/
  -  Change DB credentials in: flora-addon/logs/configDB.php 
* Action Library: Refer to function get_ActionLabel() in flora-addon/traceParser.php 
* Pattern Library: Refer to function updateProcessLabel_V3() in flora-addon/functions.php 

Knowledge of Javascript, HTML, CSS, PHP and SQL is required to proceed with any cutomisation

The updates are these two files: log_to_db.php & redis_http_server_only_record_pl.php
Replace the original log_to_db.php file to this one.
And use a swoole server to run another file.
A redis server is also needed.

https://drive.google.com/file/d/1uYvBX36qFq0kZNknYMJjyXCU-97sxBrj/view?usp=sharing
https://drive.google.com/file/d/1YUJsDSpd6qV_5DwPOz7sjalCABcCk6Gv/view?usp=sharing

For questions and queries, contact: xinyu.li1@monash.edu
 
