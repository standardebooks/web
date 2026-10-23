<?
namespace Exceptions;

/**
 * Indicates that a database deadlock requires retrying the entire transaction.
 */
class DatabaseDeadlockException extends DatabaseQueryException{
}
