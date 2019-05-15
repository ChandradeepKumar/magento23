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
        /*stage("zip file")
        {
	        steps
	        {
                echo "ZIP"
                sh 'zip -r Test.zip /root/.jenkins/workspace/Magento -x *.git*'
                echo "END - ZIP"
	        }
        }*/

        stage ("deploy")
        {
            steps
            {
                //sh 'rm -rf /root/.jenkins/workspace/Magento/Test.zip'
		    
		sh ' sshpass -p "azure@7870Sdeepu" rsync -av -e ssh --exclude='*.git' /root/.jenkins/workspace/Magento/ chandradeep@168.62.161.130:/var/www/html/ '
                //sh ' sshpass -p "azure@7870Sdeepu" scp Test.zip chandradeep@168.62.161.130:/var/www/html/ '
		//sh ' sshpass -p "azure@7870Sdeepu" ssh chandradeep@168.62.161.130 "mkdir /var/demo1" ' 
		//sh ' sshpass -p "azure@7870Sdeepu" ssh chandradeep@168.62.161.130 "unzip /var/www/html/Test.zip"'
		//sh ' sshpass -p "azure@7870Sdeepu" scp -r /root/.jenkins/workspace/Magento/ [!.git]* chandradeep@168.62.161.130:/var/www/html/ '

            }
        }

     }
 }
        
