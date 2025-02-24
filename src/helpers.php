<?php

//use Illuminate\Container\Container;
use DI\Container;
use Ody\Core\Config\Config;
use Ody\Core\Http\Request;
use Ody\Swoole\Coroutine\ContextManager;
use Ody\Swoole\ServerState;

if (! function_exists('app')) {
    function app($abstract = null, $parameters = [])
    {
        if (is_null($abstract)) {
            return new Container();
        }

        return (new Container())->make($abstract, $parameters);
    }
}

if (! function_exists('resolve')) {
    function resolve($name = null, array $parameters = [])
    {
        return app($name, $parameters);
    }
}

if (! function_exists('app_path')) {
    function app_path(string $path = null): string
    {
        return base_path("App/$path");
    }
}


if (! function_exists('basePath')) {
    function base_path(string $path = null): string
    {
        return realpath(PROJECT_PATH) . "/$path";
    }
}

if (! function_exists('configPath')) {
    function configPath(string $path = null): string
    {
        return base_path("config/$path");
    }
}

if (! function_exists('storagePath')) {
    function storagePath(string $path = null): string
    {
        return base_path("storage/$path");
    }
}

if (! function_exists('languagePath')) {
    function languagePath(string $path = null): string
    {
        return base_path("language/$path");
    }
}

if (! function_exists('databasePath')) {
    function databasePath(string $path = null): string
    {
        return base_path("database/$path");
    }
}

if (! function_exists('config')) {
    function config(string $key, string|int|bool|array|float|null $default = null): string|int|bool|array|float|null
    {
        return Config::getInstance()->get($key , $default);
    }
}

if (! function_exists('cpu_count')) {
    function cpu_count(): int
    {
        return swoole_cpu_num();
    }
}

if (! function_exists('setQueueProcessId')) {
    function setQueueProcessId(int $id): void
    {
        ServerState::getInstance()->setQueueProcessId($id);
    }
}

if (! function_exists('getMasterProcessId')) {
    function getMasterProcessId(): int|null
    {
        return ServerState::getInstance()->getMasterProcessId();
    }
}

if (! function_exists('getManagerProcessId')) {
    function getManagerProcessId(): int|null
    {
        return ServerState::getInstance()->getManagerProcessId();
    }
}

if (! function_exists('getWatcherProcessId')) {
    function getWatcherProcessId(): int|null
    {
        return ServerState::getInstance()->getWatcherProcessId();
    }
}

if (! function_exists('getFactoryProcessId')) {
    function getFactoryProcessId(): int|null
    {
        return ServerState::getInstance()->getFactoryProcessId();
    }
}

if (! function_exists('getQueueProcessId')) {
    function getQueueProcessId(): int|null
    {
        return ServerState::getInstance()->getQueueProcessId();
    }
}

if (! function_exists('getSchedulingProcessId')) {
    function getSchedulingProcessId(): int|null
    {
        return ServerState::getInstance()->getSchedulingProcessId();
    }
}

if (! function_exists('getWorkerProcessIds')) {
    function getWorkerProcessIds(): array
    {
        return ServerState::getInstance()->getWorkerProcessIds();
    }
}

if (! function_exists('httpServerIsRunning')) {
    function httpServerIsRunning(): bool
    {
        if (!is_null(getManagerProcessId()) && !is_null(getMasterProcessId())){
            return posix_kill(getManagerProcessId(), SIG_DFL) && posix_kill(getMasterProcessId(), SIG_DFL);
        }
        return false;
    }
}

if (! function_exists('queueServerIsRunning')) {
    function queueServerIsRunning(): bool
    {
        if (!is_null(getQueueProcessId())){
            return posix_kill(getQueueProcessId(), SIG_DFL);
        }
        return false;
    }
}

if (! function_exists('schedulingServerIsRunning')) {
    function schedulingServerIsRunning(): bool
    {
        if (!is_null(getSchedulingProcessId())){
            return posix_kill(getSchedulingProcessId(), SIG_DFL);
        }
        return false;
    }
}

if (! function_exists('request')) {
    function request(): Request
    {
        return Request::getInstance();
    }
}

if (! function_exists('response')) {
    function response(): Response
    {
        return Response::getInstance();
    }
}

if (! function_exists('auth')) {
    function auth(): Auth
    {
        return Auth::getInstance();
    }
}

if (! function_exists('elasticsearch')) {
    function elasticsearch(): Client
    {
        return Elasticsearch::getInstance();
    }
}

if (! function_exists('redis')) {
    function redis(): \Redis
    {
        return Redis::getInstance();
    }
}

if (! function_exists('mail')) {
    function mail(): Mail
    {
        return Mail::getInstance();
    }
}

if (! function_exists('cache')) {
    function cache(): Cache
    {
        return Cache::getInstance();
    }
}

if (! function_exists('validation')) {
    function validation(): Validation
    {
        return Validation::getInstance();
    }
}

if (!function_exists('env')) {
    function env($key, $default = null): string|bool|null
    {
        $value = getenv($key);

        if ($value === false) {
            return $default;
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }

        if (($valueLength = strlen($value)) > 1 && $value[0] === '"' && $value[$valueLength - 1] === '"') {
            return substr($value, 1, -1);
        }

        return $value;
    }
}
