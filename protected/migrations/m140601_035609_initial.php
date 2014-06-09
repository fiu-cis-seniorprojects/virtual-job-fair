<?php

class m140601_035609_initial extends CDbMigration
{
	public function safeUp()
	{
        $this->createTable('SMS', array(
            'id'=>'pk',
            'receiver_id'=>'int(11) NOT NULL',
            'sender_id'=>'int(11) NOT NULL',
            'date'=>'datetime DEFAULT NULL',
            'subject'=>'varchar(255) DEFAULT NULL',
            'Message'=>'text DEFAULT NULL',
        ), '');

        $this->createIndex('idx_receiver_id', 'SMS', 'receiver_id', FALSE);

        $this->createIndex('idx_sender_id', 'SMS', 'sender_id', FALSE);

        $this->createTable('application', array(
            'jobid'=>'int(11) NOT NULL',
            'userid'=>'int(11) NOT NULL',
            'application_date'=>'varchar(45) NOT NULL',
            'coverletter'=>'text DEFAULT NULL',
        ), '');

        $this->createIndex('idx_userid', 'application', 'userid', FALSE);

        $this->createIndex('idx_jobid', 'application', 'jobid', FALSE);

        $this->addPrimaryKey('pk_application', 'application', 'jobid,userid');

        $this->createTable('basic_info', array(
            'userid'=>'int(11) NOT NULL',
            'phone'=>'varchar(15) DEFAULT NULL',
            'city'=>'varchar(45) DEFAULT NULL',
            'state'=>'varchar(45) DEFAULT NULL',
            'about_me'=>'text DEFAULT NULL',
            'hide_phone'=>'int(11) DEFAULT NULL',
            'allowSMS'=>'int(11) DEFAULT NULL',
            'validated'=>'int(11) DEFAULT NULL',
            'smsCode'=>'int(11) DEFAULT NULL',
            'tries'=>'int(11) NOT NULL',
        ), '');

        $this->createIndex('idx_userid', 'basic_info', 'userid', FALSE);

        $this->addPrimaryKey('pk_basic_info', 'basic_info', 'userid');

        $this->createTable('company_info', array(
            'FK_userid'=>'pk',
            'name'=>'varchar(45) DEFAULT NULL',
            'street'=>'varchar(45) DEFAULT NULL',
            'street2'=>'varchar(45) DEFAULT NULL',
            'city'=>'varchar(45) DEFAULT NULL',
            'state'=>'varchar(45) DEFAULT NULL',
            'zipcode'=>'varchar(45) DEFAULT NULL',
            'website'=>'varchar(45) DEFAULT NULL',
            'description'=>'text DEFAULT NULL',
        ), '');

        $this->createIndex('idx_FK_userid', 'company_info', 'FK_userid', FALSE);

        $this->createTable('education', array(
            'id'=>'pk',
            'degree'=>'varchar(45) NOT NULL',
            'major'=>'varchar(45) NOT NULL',
            'graduation_date'=>'date NOT NULL',
            'FK_school_id'=>'int(11) DEFAULT NULL',
            'FK_user_id'=>'int(11) DEFAULT NULL',
            'gpa'=>'float DEFAULT NULL',
            'additional_info'=>'text DEFAULT NULL',
        ), '');

        $this->createIndex('idx_FK_school_id', 'education', 'FK_school_id', FALSE);

        $this->createIndex('idx_FK_user_id', 'education', 'FK_user_id', FALSE);

        $this->createTable('experience', array(
            'id'=>'pk',
            'FK_userid'=>'int(11) DEFAULT NULL',
            'company_name'=>'varchar(45) DEFAULT NULL',
            'job_title'=>'varchar(45) DEFAULT NULL',
            'job_description'=>'text DEFAULT NULL',
            'startdate'=>'datetime DEFAULT NULL',
            'enddate'=>'datetime DEFAULT NULL',
            'city'=>'varchar(45) DEFAULT NULL',
            'state'=>'varchar(45) DEFAULT NULL',
        ), '');

        $this->createIndex('idx_FK_userid', 'experience', 'FK_userid', FALSE);

        $this->createTable('handshake', array(
            'id'=>'pk',
            'jobid'=>'int(11) DEFAULT NULL',
            'employerid'=>'int(11) NOT NULL',
            'studentid'=>'int(11) NOT NULL',
        ), '');

        $this->createIndex('idx_employerid', 'handshake', 'employerid', FALSE);

        $this->createIndex('idx_jobid', 'handshake', 'jobid', FALSE);

        $this->createIndex('idx_studentid', 'handshake', 'studentid', FALSE);

        $this->createTable('job', array(
            'id'=>'pk',
            'type'=>'varchar(45) NOT NULL',
            'title'=>'varchar(45) NOT NULL',
            'FK_poster'=>'int(11) NOT NULL',
            'post_date'=>'datetime NOT NULL',
            'deadline'=>'datetime DEFAULT NULL',
            'description'=>'longtext NOT NULL',
            'compensation'=>'varchar(45) DEFAULT NULL',
            'other_requirements'=>'text DEFAULT NULL',
            'email_notification'=>'int(11) DEFAULT NULL',
            'active'=>'int(11) DEFAULT 1',
            'matches_found'=>'int(11) DEFAULT NULL',
             ), '');

         $this->createIndex('idx_FK_poster', 'job', 'FK_poster', FALSE);

         $this->createTable('job_skill_map', array(
             'id'=>'pk',
             'jobid'=>'int(11) NOT NULL',
             'skillid'=>'int(11) NOT NULL',
             'level'=>'varchar(45) DEFAULT NULL',
             'ordering'=>'int(11) DEFAULT NULL',
         ), '');

         $this->createIndex('idx_jobid', 'job_skill_map', 'jobid', FALSE);

         $this->createIndex('idx_skillid', 'job_skill_map', 'skillid', FALSE);

         $this->createTable('message', array(
             'id'=>'pk',
             'FK_receiver'=>'varchar(45) NOT NULL',
             'FK_sender'=>'varchar(45) NOT NULL',
             'message'=>'text DEFAULT NULL',
             'date'=>'datetime DEFAULT NULL',
             'been_read'=>'int(11) DEFAULT NULL',
             'been_deleted'=>'int(11) NOT NULL',
             'subject'=>'varchar(255) DEFAULT NULL',
             'userImage'=>'varchar(255) DEFAULT NULL',
         ), '');

         $this->createIndex('idx_FK_receiver', 'message', 'FK_receiver', FALSE);

         $this->createIndex('idx_FK_sender', 'message', 'FK_sender', FALSE);

         $this->createTable('notification', array(
             'id'=>'pk',
             'sender_id'=>'int(11) NOT NULL',
             'receiver_id'=>'int(11) NOT NULL',
             'datetime'=>'time NOT NULL',
             'been_read'=>'int(11) NOT NULL',
             'message'=>'varchar(5000) DEFAULT NULL',
             'link'=>'varchar(150) DEFAULT NULL',
             'importancy'=>'int(11) NOT NULL',
         ), '');

         $this->createTable('resume', array(
             'id'=>'int(11) NOT NULL',
             'resume'=>'varchar(255) DEFAULT NULL',
         ), '');

         $this->addPrimaryKey('pk_resume', 'resume', 'id');

         $this->createTable('school', array(
             'id'=>'pk',
             'name'=>'varchar(100) NOT NULL',
             'email_string'=>'varchar(45) DEFAULT NULL',
         ), '');

         $this->createTable('skillset', array(
             'id'=>'pk',
             'name'=>'varchar(45) NOT NULL',
         ), '');

         $this->createTable('student_skill_map', array(
             'id'=>'pk',
             'userid'=>'int(11) DEFAULT NULL',
             'skillid'=>'int(11) DEFAULT NULL',
             'level'=>'varchar(45) DEFAULT NULL',
             'ordering'=>'int(11) DEFAULT NULL',
         ), '');

         $this->createIndex('idx_skillid', 'student_skill_map', 'skillid', FALSE);

         $this->createIndex('idx_userid', 'student_skill_map', 'userid', FALSE);

         $this->createTable('user', array(
             'id'=>'pk',
             'username'=>'varchar(45) NOT NULL',
             'password'=>'varchar(255) DEFAULT NULL',
             'FK_usertype'=>'int(11) NOT NULL',
             'email'=>'varchar(45) NOT NULL',
             'registration_date'=>'datetime NOT NULL',
             'activation_string'=>'varchar(45) NOT NULL',
             'activated'=>'int(11) DEFAULT NULL',
             'image_url'=>'varchar(255) DEFAULT NULL',
             'first_name'=>'varchar(45) NOT NULL',
             'last_name'=>'varchar(45) NOT NULL',
             'disable'=>'int(11) DEFAULT NULL',
             'has_viewed_profile'=>'int(11) DEFAULT NULL',
             'linkedinid'=>'varchar(45) DEFAULT NULL',
             'googleid'=>'varchar(45) DEFAULT NULL',
             'fiucsid'=>'varchar(45) DEFAULT NULL',
             'hide_email'=>'int(11) DEFAULT NULL',
         ), '');

         $this->createIndex('idx_FK_usertype', 'user', 'FK_usertype', FALSE);

         $this->createTable('user_document', array(
             'id'=>'pk',
             'active_status'=>'tinyint(1) NOT NULL',
             'document_id'=>'varchar(256) NOT NULL',
             'local_user_id'=>'int(11) NOT NULL',
             'remote_user_id'=>'int(11) NOT NULL',
             'owner_id'=>'int(11) NOT NULL',
             'document_path'=>'varchar(256) NOT NULL',
             'document_name'=>'varchar(256) NOT NULL',
             'owner_url'=>'varchar(256) NOT NULL',
             'viewer_url'=>'varchar(256) NOT NULL',
         ), '');

         $this->createTable('usertype', array(
             'id'=>'pk',
             'type'=>'varchar(45) NOT NULL',
         ), '');

         $this->createTable('video_interview', array(
             'id'=>'pk',
             'FK_employer'=>'int(11) NOT NULL',
             'FK_student'=>'int(11) NOT NULL',
             'date'=>'date NOT NULL',
             'time'=>'time NOT NULL',
             'session_key'=>'varchar(45) NOT NULL',
             'notification_id'=>'varchar(45) NOT NULL',
             'ScreenShareView'=>'varchar(90) NOT NULL',
             'sharingscreen'=>'int(11) NOT NULL',
         ), '');

         $this->createTable('video_resume', array(
             'id'=>'int(11) NOT NULL',
             'video_path'=>'varchar(100) DEFAULT NULL',
         ), '');

         $this->addPrimaryKey('pk_video_resume', 'video_resume', 'id');

         $this->createTable('whiteboard_sessions', array(
             'user1'=>'varchar(15) DEFAULT NULL',
             'user2'=>'varchar(15) DEFAULT NULL',
             'interview_id'=>'varchar(20) DEFAULT NULL',
             'image_name'=>"varchar(50) DEFAULT 'none'",
         'tmpstamp'=>'timestamp NOT NULL',
         ), '');


         $this->addForeignKey('fk_SMS_user_receiver_id', 'SMS', 'receiver_id', 'user', 'id', 'NO ACTION', 'NO ACTION');

         $this->addForeignKey('fk_SMS_user_sender_id', 'SMS', 'sender_id', 'user', 'id', 'NO ACTION', 'NO ACTION');

         $this->addForeignKey('fk_application_user_userid', 'application', 'userid', 'user', 'id', 'NO ACTION', 'NO ACTION');

         $this->addForeignKey('fk_application_job_jobid', 'application', 'jobid', 'job', 'id', 'NO ACTION', 'NO ACTION');

         $this->addForeignKey('fk_basic_info_user_userid', 'basic_info', 'userid', 'user', 'id', 'NO ACTION', 'NO ACTION');

         $this->addForeignKey('fk_company_info_user_FK_userid', 'company_info', 'FK_userid', 'user', 'id', 'NO ACTION', 'NO ACTION');

         $this->addForeignKey('fk_education_school_FK_school_id', 'education', 'FK_school_id', 'school', 'id', 'NO ACTION', 'NO ACTION');

         $this->addForeignKey('fk_education_user_FK_user_id', 'education', 'FK_user_id', 'user', 'id', 'NO ACTION', 'NO ACTION');

         $this->addForeignKey('fk_experience_user_FK_userid', 'experience', 'FK_userid', 'user', 'id', 'NO ACTION', 'NO ACTION');

         $this->addForeignKey('fk_handshake_user_employerid', 'handshake', 'employerid', 'user', 'id', 'NO ACTION', 'NO ACTION');

         $this->addForeignKey('fk_handshake_job_jobid', 'handshake', 'jobid', 'job', 'id', 'NO ACTION', 'NO ACTION');

         $this->addForeignKey('fk_handshake_user_studentid', 'handshake', 'studentid', 'user', 'id', 'NO ACTION', 'NO ACTION');

         $this->addForeignKey('fk_job_user_FK_poster', 'job', 'FK_poster', 'user', 'id', 'NO ACTION', 'NO ACTION');

         $this->addForeignKey('fk_job_skill_map_job_jobid', 'job_skill_map', 'jobid', 'job', 'id', 'NO ACTION', 'NO ACTION');

         $this->addForeignKey('fk_job_skill_map_skillset_skillid', 'job_skill_map', 'skillid', 'skillset', 'id', 'NO ACTION', 'NO ACTION');

         $this->addForeignKey('fk_message_user_FK_receiver', 'message', 'FK_receiver', 'user', 'username', 'NO ACTION', 'NO ACTION');

         $this->addForeignKey('fk_message_user_FK_sender', 'message', 'FK_sender', 'user', 'username', 'NO ACTION', 'NO ACTION');

         $this->addForeignKey('fk_student_skill_map_skillset_skillid', 'student_skill_map', 'skillid', 'skillset', 'id', 'NO ACTION', 'NO ACTION');

         $this->addForeignKey('fk_student_skill_map_user_userid', 'student_skill_map', 'userid', 'user', 'id', 'NO ACTION', 'NO ACTION');

         $this->addForeignKey('fk_user_usertype_FK_usertype', 'user', 'FK_usertype', 'usertype', 'id', 'NO ACTION', 'NO ACTION');

    }

	public function safeDown()
	{
        $this->dropForeignKey('fk_SMS_user_receiver_id', 'SMS');

        $this->dropForeignKey('fk_SMS_user_sender_id', 'SMS');

        $this->dropForeignKey('fk_application_user_userid', 'application');

        $this->dropForeignKey('fk_application_job_jobid', 'application');

        $this->dropForeignKey('fk_basic_info_user_userid', 'basic_info');

        $this->dropForeignKey('fk_company_info_user_FK_userid', 'company_info');

        $this->dropForeignKey('fk_education_school_FK_school_id', 'education');

        $this->dropForeignKey('fk_education_user_FK_user_id', 'education');

        $this->dropForeignKey('fk_experience_user_FK_userid', 'experience');

        $this->dropForeignKey('fk_handshake_user_employerid', 'handshake');

        $this->dropForeignKey('fk_handshake_job_jobid', 'handshake');

        $this->dropForeignKey('fk_handshake_user_studentid', 'handshake');

        $this->dropForeignKey('fk_job_user_FK_poster', 'job');

        $this->dropForeignKey('fk_job_skill_map_job_jobid', 'job_skill_map');

        $this->dropForeignKey('fk_job_skill_map_skillset_skillid', 'job_skill_map');

        $this->dropForeignKey('fk_message_user_FK_receiver', 'message');

        $this->dropForeignKey('fk_message_user_FK_sender', 'message');

        $this->dropForeignKey('fk_student_skill_map_skillset_skillid', 'student_skill_map');

        $this->dropForeignKey('fk_student_skill_map_user_userid', 'student_skill_map');

        $this->dropForeignKey('fk_user_usertype_FK_usertype', 'user');

        $this->dropTable('SMS');
        $this->dropTable('application');
        $this->dropTable('basic_info');
        $this->dropTable('company_info');
        $this->dropTable('education');
        $this->dropTable('experience');
        $this->dropTable('handshake');
        $this->dropTable('job');
        $this->dropTable('job_skill_map');
        $this->dropTable('message');
        $this->dropTable('notification');
        $this->dropTable('resume');
        $this->dropTable('school');
        $this->dropTable('skillset');
        $this->dropTable('student_skill_map');
        $this->dropTable('user');
        $this->dropTable('user_document');
        $this->dropTable('usertype');
        $this->dropTable('video_interview');
        $this->dropTable('video_resume');
        $this->dropTable('whiteboard_sessions');
	}
}