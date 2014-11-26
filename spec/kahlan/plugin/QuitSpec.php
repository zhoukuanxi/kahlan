<?php
namespace spec\kahlan\plugin;

use jit\Interceptor;
use jit\Patchers;
use jit\Parser;
use kahlan\QuitException;
use kahlan\plugin\Quit;
use kahlan\jit\patcher\Quit as QuitPatcher;

use spec\fixture\plugin\quit\Foo;

describe("Quit", function() {

    /**
     * Save current & reinitialize the Interceptor class.
     */
    before(function() {
        $this->previous = Interceptor::instance();
        Interceptor::unpatch();

        $patchers = new Patchers();
        $patchers->add('quit', new QuitPatcher());
        $cachePath = rtrim(sys_get_temp_dir(), DS) . DS . 'kahlan';
        Interceptor::patch(compact('patchers', 'cachePath'));
    });

    /**
     * Restore Interceptor class.
     */
    after(function() {
        Interceptor::load($this->previous);
    });

    describe("::disable()", function() {

        it("throws an exception when an exit statement occurs if not allowed", function() {

            Quit::disable();

            $closure = function() {
                $foo = new Foo();
                $foo->exitStatement(-1);
            };

            expect($closure)->toThrow(new QuitException('Exit statement occured', -1));

        });

    });

});
