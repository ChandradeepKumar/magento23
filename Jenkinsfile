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
        
        stage ("deploy")
        {
            steps
            {
                //sh 'rm -rf /root/.jenkins/workspace/php/Test.zip'
                sh ' sshpass -p "azure@7870Sdeepu" scp -r [!.git]* chandradeep@168.62.161.130:/var/www/html/ '
            }
        }

     }
 }
        
