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
                sh 'zip -r Test.zip /root/.jenkins/workspace/Magento'
                echo "END - ZIP"
	        }
        }*/

        stage ("deploy")
        {
            steps
            {
                //sh 'rm -rf /root/.jenkins/workspace/Magento/Test.zip'
                sh ' sshpass -p "azure@7870Sdeepu" scp Test.zip chandradeep@168.62.161.130:/var/www/html/ '
		sh ' sshpass -p "azure@7870Sdeepu" chandradeep@168.62.161.130 "sudo mkdir /var/demo" ' 
		//sh 'sshpass -p "azure@7870Sdeepu" -i chandradeep@168.62.161.130 -c "sudo unzip /var/www/html/Test.zip"'
		//sh ' sshpass -p "azure@7870Sdeepu" scp -r /root/.jenkins/workspace/Magento/ [!.git]* chandradeep@168.62.161.130:/var/www/html/ '

            }
        }

     }
 }
        
