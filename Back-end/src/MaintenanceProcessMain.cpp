// ODAMongoFrameWork_Demo.cpp : Defines the entry point for the console application.
//

#include "Processor/MaintenanceProcess.h"
#include "Common/Common.h"
#include "Common/DBCommon.h"
//------------------------------------
#include <ctime>
#include <stdio.h>
#include <stdlib.h>
#include <sys/wait.h>
#include <sys/types.h>
#include <errno.h>
#include <unistd.h>
#include <pthread.h>
#include <dirent.h>

//------------------------------------
extern "C"
{
   #include <pthread.h>
}
#include <iostream>
using namespace std;

#define FunctionConfig "FunctionConfig.ini"

pid_t CheckProc(const char* name);

int main(int argc, char* argv[])
{
	int child_id;
	string strFunction;
	ifstream ifsCfgFile;

	if(CheckProc("MaintenanceGo") != -1)
	{
		printf ("Process is existed !!\n");
		return 0;
	}
	ifsCfgFile.open(FunctionConfig);
	
	if (ifsCfgFile.is_open())
	{
		while(!ifsCfgFile.eof())
		{
			getline(ifsCfgFile, strFunction);
			strFunction = CUtilities::ReplaceString(strFunction, " ", "");
			strFunction = CUtilities::ReplaceString(strFunction, "\r", "");
			strFunction = CUtilities::ReplaceString(strFunction, "\n", "");
			
			//===============================FORK==================================//	
			child_id = fork();
			if (child_id) 
			{
				cout << "I'm parent of " << child_id << endl;
			}
			else 
			{
				CMaintenanceProcess objMaintenanceProcess("SyncConfig.ini");
				objMaintenanceProcess.GoToWorkFlow(strFunction);
			}
		}
		ifsCfgFile.close();
	}
	
	return 0;
}

pid_t CheckProc(const char* name) 
{
	DIR* dir;
    struct dirent* ent;
    char buf[512];

    long  pid;
    char pname[100] = {0,};
    char state;
    FILE *fp=NULL; 
	int nCount = 0;

    if (!(dir = opendir("/proc"))) {
        perror("can't open /proc");
        return -1;
    }

    while((ent = readdir(dir)) != NULL) {
        long lpid = atol(ent->d_name);
        if(lpid < 0)
            continue;
        snprintf(buf, sizeof(buf), "/proc/%ld/stat", lpid);
        fp = fopen(buf, "r");

        if (fp) {
            if ( (fscanf(fp, "%ld (%[^)]) %c", &pid, pname, &state)) != 3 ){
                printf("fscanf failed \n");
                fclose(fp);
                closedir(dir);
                return -1; 
            }
            if (!strcmp(pname, name)) {
				if(nCount == 0)
				{
					nCount = 1;
				}
				else
				{
                fclose(fp);
                closedir(dir);
				return (pid_t)lpid;
				}
            }
            fclose(fp);
        }
    }

	closedir(dir);
	return -1;
}