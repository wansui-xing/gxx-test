<?php
/**
 * Created by PhpStorm.
 * User: WIN10
 * Date: 2019/8/28
 * Time: 15:07
 */

namespace app\command;


use app\index\model\user;
use think\console\Command;
use think\console\Input;
use think\console\Output;

class Test extends Command
{
    protected function configure()
    {
        $this->setName('Test')->setDescription('耿星星的计划任务');
    }


    protected function execute(Input $input,Output $output)
    {
        $this->doCron();
        $output->writeln('TestCommand');
    }


    public function doCron()
    {
        $user = new user();
        $users = $user->where('id','>','0')->select();
        $users = collection($users)->toArray();
        if (count($users ) > 0){
            foreach ($users as &$u){
                $u['score']= $u['score'] +1;
                unset($u);
            }
            $result = $user->saveAll($users);
        }
    }


}