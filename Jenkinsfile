pipeline
{
    agent  any
    stages
    {
        
       
        stage ("syntax error check")
        {
            steps
            {
                echo "This is Demo"
            }
        }
	 
	stage ("Image Build")
	{
	    steps
	    {
		    sh 'docker build -t image1 .' //build image
	    }
	}
	stage('Image Run')
        {
            steps
            {
                sh 'docker rm -f cont1'
                sh 'docker run --name cont1 -i -d -p 9096:80 image1 '
            }
        }

	/*stage ("sonar")
        {
            steps
            {
                //sh 'sonar-scanner -Dsonar.projectKey="abc" -Dsonar.sources=.'
                sh '/opt/sonar-scanner-3.3.0.1492-linux/bin/sonar-scanner'
            }
        }
	    
        stage("zip file")
        {
	        steps
	        {
	                echo "ZIP"
			sh 'rm -f Test.zip'
			sh 'cd .. && zip -r Test.zip Magento --exclude *.git*'
			sh 'mv ../Test.zip ./'
			
			// sh 'zip -r Test.zip /root/.jenkins/workspace/Magento --exclude *.git* '
			// sh 'zip -r Test.zip ../Magento --exclude *.git* '
			echo "END - ZIP"
	        }
        }*/

	
        
        /*stage ("deploy")
        {
            steps
            {
                //sh 'rm -rf /root/.jenkins/workspace/Magento/Test.zip'
		    
		//sh ' sshpass -p "azure@7870Sdeepu" rsync -rav -e ssh --exclude='*.git' /root/.jenkins/workspace/Magento/ root@168.62.161.130:/var/www/html/ '
                sh ' sshpass -p "azure@7870Sdeepu" scp Test.zip chandradeep@168.62.161.130:/var/www/html/ '
		//sh ' sshpass -p "azure@7870Sdeepu" ssh chandradeep@168.62.161.130 "mkdir /var/demo1" ' 
		sh ' sshpass -p "azure@7870Sdeepu" ssh chandradeep@168.62.161.130 "unzip -o /var/www/html/Test.zip -d /var/www/html"'
		//sh ' sshpass -p "azure@7870Sdeepu" ssh chandradeep@168.162.161.130 "cp -r /home/chandradeep/root/.jenkins/workspace/Magento /var/www/html" '
		//sh ' sshpass -p "azure@7870Sdeepu" scp -r [!.]* chandradeep@168.62.161.130:/var/www/html/ '

            }
        }*/
	
     }

    /*post 
    {
        success 
        {
              
            mail to: "chandradeep.kumar@nagarro.com",
            subject: "SUCCESS: ${currentBuild.fullDisplayName}",
            body: "successfull ${env.BUILD_URL}" 
 

            //emailext body: 'successful: ${env.BUILD_URL}', subject: 'Testing', to: 'chandradeep.kumar@nagarro.com'
        }
                
        failure
        {
            emailext body: 'failure: ${env.BUILD_URL}' , subject: 'testing', to: 'chandradeep.kumar@nagarro.com'
        }
                
    }*/
 }
