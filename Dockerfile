FROM centos:7

RUN sudo yum install zip

expose 8080
CMD ["/bin/bash"]
